<?php
/**
 * Newsletter — subscribers, unsubscribe links, and emailing blog posts.
 *
 * Subscribers live in MySQL (newsletter_subscribers). data/subscribers.txt is a
 * backup written on every new signup so nobody is lost while the database is
 * unreachable; the admin side imports it back into the table.
 *
 * Emailing a post is a queue (newsletter_deliveries, one row per post + address)
 * worked through in small batches from the admin, so a long list never hits the
 * host's request time limit and nobody receives the same post twice.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../mailer_contact/mail.php';
require_once __DIR__ . '/../mailer_contact/newsletter-template.php';

const NEWSLETTER_FILE = __DIR__ . '/subscribers.txt';
const NEWSLETTER_LOGO = __DIR__ . '/../assets/img/logo.png';

// ─── Addresses & links ────────────────────────────────────────────────────────

function newsletter_normalize_email(string $email): ?string {
    $email = strtolower(trim($email));
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
}

// Signed so an unsubscribe link only works for the address it was sent to.
function newsletter_token(string $email): string {
    return hash_hmac('sha256', strtolower($email), NEWSLETTER_SECRET);
}

function newsletter_token_valid(string $email, string $token): bool {
    return hash_equals(newsletter_token($email), $token);
}

// Email links must point at the host that actually serves the site. SITE_URL is
// the long-term domain, but while the site runs on sorwatom.rf.gd use the request
// host — only when it's one we recognise, never an arbitrary Host header.
function newsletter_base_url(): string {
    $host  = strtolower($_SERVER['HTTP_HOST'] ?? '');
    $known = [parse_url(SITE_URL, PHP_URL_HOST), 'sorwatom.rf.gd', 'www.sorwatom.rf.gd'];
    return in_array($host, $known, true) ? 'https://' . $host : SITE_URL;
}

function newsletter_unsubscribe_url(string $email): string {
    return newsletter_base_url() . '/unsubscribe?' . http_build_query(['e' => $email, 't' => newsletter_token($email)]);
}

// Uploaded images are stored as site-relative paths; email needs absolute URLs.
function newsletter_absolute_url(string $url): string {
    if ($url === '' || preg_match('#^https?://#i', $url)) return $url;
    return str_starts_with($url, '/') ? newsletter_base_url() . $url : '';
}

// ─── Storage ──────────────────────────────────────────────────────────────────

function newsletter_db(bool $with_deliveries = false): ?PDO {
    static $ready = [];
    $db = get_db();
    if (!$db) return null;

    if (empty($ready['subscribers'])) {
        $db->exec("CREATE TABLE IF NOT EXISTS newsletter_subscribers (
            id            INT AUTO_INCREMENT PRIMARY KEY,
            email         VARCHAR(255) NOT NULL,
            subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            status        ENUM('active','unsubscribed') DEFAULT 'active',
            UNIQUE KEY uk_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $ready['subscribers'] = true;
    }

    if ($with_deliveries && empty($ready['deliveries'])) {
        $db->exec("CREATE TABLE IF NOT EXISTS newsletter_deliveries (
            id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            post_id    INT UNSIGNED NOT NULL,
            email      VARCHAR(255) NOT NULL,
            status     ENUM('pending','sending','sent','failed','skipped') NOT NULL DEFAULT 'pending',
            attempts   TINYINT UNSIGNED NOT NULL DEFAULT 0,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            sent_at    DATETIME NULL,
            UNIQUE KEY uk_post_email (post_id, email),
            INDEX idx_post_status (post_id, status),
            FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $ready['deliveries'] = true;
    }

    return $db;
}

/** @return array<string, string> email => date first seen */
function newsletter_file_emails(): array {
    $emails = [];
    foreach (@file(NEWSLETTER_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        [$date, $email] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
        if (($email = newsletter_normalize_email($email)) && !isset($emails[$email])) {
            $emails[$email] = $date;
        }
    }
    return $emails;
}

// Adds anyone who signed up while the database was down. Existing rows are left
// alone, so people who unsubscribed stay unsubscribed.
function newsletter_import_file(PDO $db): void {
    $known = array_flip(array_map('strtolower',
        $db->query("SELECT email FROM newsletter_subscribers")->fetchAll(PDO::FETCH_COLUMN)));
    $ins = $db->prepare("INSERT IGNORE INTO newsletter_subscribers (email, subscribed_at) VALUES (?, ?)");
    foreach (newsletter_file_emails() as $email => $date) {
        if (!isset($known[$email])) {
            $ins->execute([$email, strtotime($date) ? $date : date('Y-m-d H:i:s')]);
        }
    }
}

// ─── Subscribe / unsubscribe ──────────────────────────────────────────────────

/**
 * Records a signup. Returns true for a new or returning subscriber (who should get
 * a welcome email) and false when the address was already on the list.
 */
function newsletter_subscribe(string $email): bool {
    $in_file = isset(newsletter_file_emails()[$email]);
    if (!$in_file) {
        @file_put_contents(NEWSLETTER_FILE, date('Y-m-d H:i:s') . ' | ' . $email . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    try {
        $db = newsletter_db();
        if ($db) {
            // Affected rows: 1 = inserted, 2 = re-activated, 0 = already active.
            $stmt = $db->prepare(
                "INSERT INTO newsletter_subscribers (email) VALUES (?)
                 ON DUPLICATE KEY UPDATE status = 'active'"
            );
            $stmt->execute([$email]);
            return $stmt->rowCount() > 0;
        }
    } catch (Throwable $e) {
        // Database unavailable — the backup file above still has them.
    }
    return !$in_file;
}

function newsletter_unsubscribe(string $email): bool {
    $db = newsletter_db();
    if (!$db) return false;
    $db->prepare(
        "INSERT INTO newsletter_subscribers (email, status) VALUES (?, 'unsubscribed')
         ON DUPLICATE KEY UPDATE status = 'unsubscribed'"
    )->execute([$email]);
    return true;
}

function newsletter_list_subscribers(): array {
    $db = newsletter_db();
    newsletter_import_file($db);
    return $db->query("SELECT email, subscribed_at, status FROM newsletter_subscribers
                       ORDER BY status = 'active' DESC, subscribed_at DESC")->fetchAll();
}

function newsletter_active_count(): int {
    $db = newsletter_db();
    newsletter_import_file($db);
    return (int) $db->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'active'")->fetchColumn();
}

// ─── Emails ───────────────────────────────────────────────────────────────────

// Post fields the email templates need, with absolute URLs.
function newsletter_post_view(array $post): array {
    $excerpt = trim($post['excerpt'] ?? '');
    if ($excerpt === '') {
        $text  = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($post['content'] ?? ''), ENT_QUOTES, 'UTF-8')));
        $words = explode(' ', $text);
        $excerpt = implode(' ', array_slice($words, 0, 45)) . (count($words) > 45 ? '…' : '');
    }
    return [
        'title'        => $post['title'],
        'excerpt'      => $excerpt,
        'category'     => $post['category_name'] ?? '',
        'author'       => $post['author_name'] ?? '',
        'date'         => date('j F Y', strtotime($post['published_at'] ?: 'now')),
        'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($post['content'] ?? '')) / 200)),
        'url'          => newsletter_base_url() . '/blog/' . rawurlencode($post['slug']),
        'image'        => newsletter_absolute_url(trim($post['image_url'] ?? '')),
    ];
}

function newsletter_get_post(int $post_id): ?array {
    $stmt = get_db()->prepare(
        "SELECT p.*, c.name AS category_name, a.name AS author_name
         FROM blog_posts p
         LEFT JOIN blog_categories c ON p.category_id = c.id
         LEFT JOIN blog_authors    a ON p.author_id   = a.id
         WHERE p.id = ? AND p.status = 'published'"
    );
    $stmt->execute([$post_id]);
    return $stmt->fetch() ?: null;
}

function newsletter_send_post_to(array $view, string $email, mail $mailer, ?string $subject = null): bool {
    $unsub = newsletter_unsubscribe_url($email);
    return $mailer->send($email, $subject ?? $view['title'], newsletter_post_html($view, $unsub, 'cid:sorwatom-logo'), '', [
        'alt'       => newsletter_post_text($view, $unsub),
        'from_name' => 'Sorwatom',
        'embed'     => ['sorwatom-logo' => NEWSLETTER_LOGO],
        'headers'   => ['List-Unsubscribe' => '<' . $unsub . '>'],
    ]);
}

function newsletter_send_welcome(string $email): bool {
    $latest = [];
    try {
        $stmt = get_db()?->query(
            "SELECT p.title, p.slug, c.name AS category_name
             FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id
             WHERE p.status = 'published' ORDER BY p.published_at DESC LIMIT 3"
        );
        foreach ($stmt ? $stmt->fetchAll() : [] as $p) {
            $latest[] = [
                'title'    => $p['title'],
                'category' => $p['category_name'] ?? '',
                'url'      => newsletter_base_url() . '/blog/' . rawurlencode($p['slug']),
            ];
        }
    } catch (Throwable $e) {
        // Welcome email still goes out, just without recent posts.
    }

    $blog  = newsletter_base_url() . '/blog';
    $unsub = newsletter_unsubscribe_url($email);
    return (new mail())->send($email, 'Welcome to the Sorwatom newsletter', newsletter_welcome_html($latest, $blog, $unsub, 'cid:sorwatom-logo'), '', [
        'alt'       => newsletter_welcome_text($latest, $blog, $unsub),
        'from_name' => 'Sorwatom',
        'embed'     => ['sorwatom-logo' => NEWSLETTER_LOGO],
        'headers'   => ['List-Unsubscribe' => '<' . $unsub . '>'],
    ]);
}

// ─── Sending a post to the list ───────────────────────────────────────────────

/**
 * Delivery counts for a post plus how many active subscribers haven't received it.
 */
function newsletter_post_status(int $post_id): array {
    $db = newsletter_db(true);
    newsletter_import_file($db);

    $status = ['pending' => 0, 'sending' => 0, 'sent' => 0, 'failed' => 0, 'skipped' => 0];
    $stmt = $db->prepare("SELECT status, COUNT(*) FROM newsletter_deliveries WHERE post_id = ? GROUP BY status");
    $stmt->execute([$post_id]);
    foreach ($stmt->fetchAll(PDO::FETCH_KEY_PAIR) as $k => $n) $status[$k] = (int) $n;

    $stmt = $db->prepare("SELECT MAX(sent_at) FROM newsletter_deliveries WHERE post_id = ?");
    $stmt->execute([$post_id]);
    $status['last_sent_at'] = $stmt->fetchColumn() ?: null;

    $stmt = $db->prepare(
        "SELECT COUNT(*) FROM newsletter_subscribers s
         WHERE s.status = 'active' AND NOT EXISTS (
             SELECT 1 FROM newsletter_deliveries d
             WHERE d.post_id = ? AND d.email = s.email AND d.status = 'sent')"
    );
    $stmt->execute([$post_id]);
    $status['remaining'] = (int) $stmt->fetchColumn();

    return $status;
}

/**
 * Adds every active subscriber who hasn't had this post yet to the queue, and
 * puts failed sends (and sends abandoned by a crashed request) back in line.
 */
function newsletter_queue_post(int $post_id): void {
    $db = newsletter_db(true);
    newsletter_import_file($db);

    $db->prepare(
        "INSERT IGNORE INTO newsletter_deliveries (post_id, email)
         SELECT ?, email FROM newsletter_subscribers WHERE status = 'active'"
    )->execute([$post_id]);

    $db->prepare(
        "UPDATE newsletter_deliveries d
         JOIN newsletter_subscribers s ON s.email = d.email AND s.status = 'active'
         SET d.status = 'pending'
         WHERE d.post_id = ?
           AND (d.status IN ('failed', 'skipped')
                OR (d.status = 'sending' AND d.updated_at < NOW() - INTERVAL 10 MINUTE))"
    )->execute([$post_id]);
}

/**
 * Sends up to $max queued emails for a post, stopping early after $seconds so one
 * request stays well inside the host's time limit. Call repeatedly until
 * 'pending' is 0. Returns newsletter_post_status().
 */
function newsletter_send_batch(int $post_id, int $max = 8, int $seconds = 20): array {
    $db   = newsletter_db(true);
    $post = newsletter_get_post($post_id);
    if (!$post) throw new RuntimeException('Only published posts can be emailed.');

    // Anyone who unsubscribed after the post was queued is skipped.
    $db->prepare(
        "UPDATE newsletter_deliveries d
         LEFT JOIN newsletter_subscribers s ON s.email = d.email
         SET d.status = 'skipped'
         WHERE d.post_id = ? AND d.status = 'pending' AND (s.email IS NULL OR s.status <> 'active')"
    )->execute([$post_id]);

    $pick = $db->prepare("SELECT id, email FROM newsletter_deliveries WHERE post_id = ? AND status = 'pending' ORDER BY id LIMIT ?");
    $pick->bindValue(1, $post_id, PDO::PARAM_INT);
    $pick->bindValue(2, $max, PDO::PARAM_INT);
    $pick->execute();

    // Claiming a row first means two open admin tabs can't both send it.
    $claim  = $db->prepare("UPDATE newsletter_deliveries SET status = 'sending', attempts = attempts + 1 WHERE id = ? AND status = 'pending'");
    $sent   = $db->prepare("UPDATE newsletter_deliveries SET status = 'sent', sent_at = NOW() WHERE id = ?");
    $failed = $db->prepare("UPDATE newsletter_deliveries SET status = 'failed' WHERE id = ?");

    $view   = newsletter_post_view($post);
    $mailer = new mail();
    $start  = time();
    $error  = '';

    foreach ($pick->fetchAll() as $row) {
        if (time() - $start >= $seconds) break;
        $claim->execute([$row['id']]);
        if ($claim->rowCount() !== 1) continue;

        $ok = false;
        try {
            $ok = newsletter_send_post_to($view, $row['email'], $mailer);
            if (!$ok) $error = trim($mailer->error);
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
        ($ok ? $sent : $failed)->execute([$row['id']]);
    }

    return newsletter_post_status($post_id) + ['last_error' => $error];
}
