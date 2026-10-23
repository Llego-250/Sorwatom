<?php
require_once __DIR__ . '/../config/database.php';

// ─── Helpers ──────────────────────────────────────────────────────────────────

function blog_reading_time(string $html): int {
    return max(1, (int) ceil(str_word_count(strip_tags($html)) / 200));
}

function blog_format_date(string $date): string {
    $ts = strtotime($date);
    // Month names follow the site language when it is loaded (admin and the RSS feed stay English)
    return function_exists('__date') ? __date($ts) : date('j F Y', $ts);
}

// A published post dated in the future is scheduled — not on the site yet.
function blog_is_scheduled(array $post): bool {
    return ($post['status'] ?? '') === 'published' && ($post['published_at'] ?? '') > date('Y-m-d H:i:s');
}

function blog_hydrate(array $post): array {
    $post['reading_time']   = blog_reading_time($post['content'] ?? '');
    $post['date_formatted'] = blog_format_date($post['published_at'] ?? $post['created_at']);
    $post['tags']           = blog_get_tags((int) $post['id']);
    return $post;
}

// ─── Queries ──────────────────────────────────────────────────────────────────
// Without a database connection (e.g. locally — the host only accepts its own
// servers) reads return nothing, so pages show their empty state instead of failing.

// Scheduled posts stay off the site, feed and newsletter until their date.
// For the posts table aliased as p.
const BLOG_LIVE = "p.status = 'published' AND (p.published_at IS NULL OR p.published_at <= NOW())";

function blog_get_posts(int $page = 1, int $per_page = 9, string $category_slug = ''): array {
    $db     = get_db();
    if (!$db) return [];
    $offset = ($page - 1) * $per_page;
    $params = [];

    $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                   a.name AS author_name, a.bio AS author_bio, a.avatar_url AS author_avatar
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            LEFT JOIN blog_authors    a ON p.author_id   = a.id
            WHERE " . BLOG_LIVE;

    if ($category_slug && $category_slug !== 'all') {
        $sql .= " AND c.slug = :cat";
        $params[':cat'] = $category_slug;
    }

    $sql .= " ORDER BY p.published_at DESC LIMIT :lim OFFSET :off";

    $stmt = $db->prepare($sql);
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset,   PDO::PARAM_INT);
    $stmt->execute();

    return array_map('blog_hydrate', $stmt->fetchAll());
}

function blog_count_posts(string $category_slug = ''): int {
    $db     = get_db();
    if (!$db) return 0;
    $params = [];

    $sql = "SELECT COUNT(*) FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE " . BLOG_LIVE;

    if ($category_slug && $category_slug !== 'all') {
        $sql .= " AND c.slug = :cat";
        $params[':cat'] = $category_slug;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function blog_get_featured(): ?array {
    $db   = get_db();
    if (!$db) return null;
    $stmt = $db->query(
        "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                a.name AS author_name
         FROM blog_posts p
         LEFT JOIN blog_categories c ON p.category_id = c.id
         LEFT JOIN blog_authors    a ON p.author_id   = a.id
         WHERE " . BLOG_LIVE . "
         ORDER BY p.published_at DESC LIMIT 1"
    );
    $post = $stmt->fetch();
    return $post ? blog_hydrate($post) : null;
}

function blog_get_by_slug(string $slug): ?array {
    $db   = get_db();
    if (!$db) return null;
    $stmt = $db->prepare(
        "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                a.name AS author_name, a.bio AS author_bio, a.avatar_url AS author_avatar
         FROM blog_posts p
         LEFT JOIN blog_categories c ON p.category_id = c.id
         LEFT JOIN blog_authors    a ON p.author_id   = a.id
         WHERE p.slug = :slug AND " . BLOG_LIVE
    );
    $stmt->execute([':slug' => $slug]);
    $post = $stmt->fetch();
    return $post ? blog_hydrate($post) : null;
}

function blog_get_related(int $post_id, int $category_id, int $limit = 3): array {
    $db   = get_db();
    if (!$db) return [];
    $stmt = $db->prepare(
        "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                a.name AS author_name
         FROM blog_posts p
         LEFT JOIN blog_categories c ON p.category_id = c.id
         LEFT JOIN blog_authors    a ON p.author_id   = a.id
         WHERE " . BLOG_LIVE . " AND p.category_id = :cat AND p.id != :id
         ORDER BY p.published_at DESC LIMIT :lim"
    );
    $stmt->bindValue(':cat', $category_id, PDO::PARAM_INT);
    $stmt->bindValue(':id',  $post_id,     PDO::PARAM_INT);
    $stmt->bindValue(':lim', $limit,       PDO::PARAM_INT);
    $stmt->execute();

    return array_map(function($p) {
        $p['date_formatted'] = blog_format_date($p['published_at']);
        return $p;
    }, $stmt->fetchAll());
}

function blog_get_categories(): array {
    $db   = get_db();
    if (!$db) return [];
    $stmt = $db->query(
        "SELECT c.*, COUNT(p.id) AS post_count
         FROM blog_categories c
         LEFT JOIN blog_posts p ON c.id = p.category_id AND " . BLOG_LIVE . "
         GROUP BY c.id HAVING post_count > 0 ORDER BY c.name"
    );
    return $stmt->fetchAll();
}

function blog_get_tags(int $post_id): array {
    $db   = get_db();
    if (!$db) return [];
    $stmt = $db->prepare("SELECT tag FROM blog_post_tags WHERE post_id = :id ORDER BY tag");
    $stmt->execute([':id' => $post_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// ─── Admin writes ─────────────────────────────────────────────────────────────

// Columns added after the original setup, created the first time they're needed.
// auto_email: email subscribers automatically when the post goes live;
// auto_emailed_at: when that happened, so it happens only once.
function blog_ensure_schema(PDO $db): void {
    static $done = false;
    if ($done) return;
    if (!$db->query("SHOW COLUMNS FROM blog_posts LIKE 'auto_email'")->fetch()) {
        $db->exec("ALTER TABLE blog_posts
            ADD COLUMN auto_email      TINYINT(1) NOT NULL DEFAULT 0,
            ADD COLUMN auto_emailed_at DATETIME NULL");
    }
    $done = true;
}

function blog_save_post(array $data, ?int $id = null): int {
    $db = get_db();
    blog_ensure_schema($db);

    $fields = ['slug','title','excerpt','content','category_id','author_id',
               'image_url','status','published_at','auto_email'];

    if ($id) {
        $set  = implode(', ', array_map(fn($f) => "$f = :$f", $fields));
        $stmt = $db->prepare("UPDATE blog_posts SET $set WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    } else {
        $cols = implode(', ', $fields);
        $vals = ':' . implode(', :', $fields);
        $stmt = $db->prepare("INSERT INTO blog_posts ($cols) VALUES ($vals)");
    }

    foreach ($fields as $f) {
        $stmt->bindValue(":$f", $data[$f] ?? null);
    }
    $stmt->execute();

    $post_id = $id ?? (int) $db->lastInsertId();

    // Replace tags
    $db->prepare("DELETE FROM blog_post_tags WHERE post_id = :id")->execute([':id' => $post_id]);
    if (!empty($data['tags'])) {
        $ins = $db->prepare("INSERT INTO blog_post_tags (post_id, tag) VALUES (:pid, :tag)");
        foreach ($data['tags'] as $tag) {
            $tag = trim($tag);
            if ($tag !== '') $ins->execute([':pid' => $post_id, ':tag' => $tag]);
        }
    }

    return $post_id;
}

function blog_delete_post(int $id): void {
    $db = get_db();
    $db->prepare("DELETE FROM blog_posts WHERE id = :id")->execute([':id' => $id]);
}

function blog_get_all_posts_admin(): array {
    $db   = get_db();
    $stmt = $db->query(
        "SELECT p.id, p.slug, p.title, p.status, p.published_at,
                c.name AS category_name, a.name AS author_name
         FROM blog_posts p
         LEFT JOIN blog_categories c ON p.category_id = c.id
         LEFT JOIN blog_authors    a ON p.author_id   = a.id
         ORDER BY p.created_at DESC"
    );
    return $stmt->fetchAll();
}

function blog_get_post_admin(int $id): ?array {
    $db   = get_db();
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $post = $stmt->fetch();
    if (!$post) return null;
    $post['tags'] = blog_get_tags($id);
    return $post;
}

function blog_get_authors(): array {
    return get_db()->query("SELECT * FROM blog_authors ORDER BY name")->fetchAll();
}

function blog_get_all_categories_admin(): array {
    return get_db()->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
}

// "Other" in the post editor: reuse a category or author with the same name
// (ignoring case), otherwise add it. Returns its id.
function blog_find_or_create_category(string $name): int {
    $db   = get_db();
    $slug = blog_slugify($name) ?: 'category-' . substr(md5(mb_strtolower($name)), 0, 8);
    $find = $db->prepare("SELECT id FROM blog_categories WHERE slug = ? OR LOWER(name) = LOWER(?) LIMIT 1");
    $find->execute([$slug, $name]);
    if ($id = $find->fetchColumn()) return (int) $id;
    $db->prepare("INSERT INTO blog_categories (name, slug) VALUES (?, ?)")->execute([$name, $slug]);
    return (int) $db->lastInsertId();
}

function blog_find_or_create_author(string $name): int {
    $db   = get_db();
    $find = $db->prepare("SELECT id FROM blog_authors WHERE LOWER(name) = LOWER(?) LIMIT 1");
    $find->execute([$name]);
    if ($id = $find->fetchColumn()) return (int) $id;
    $db->prepare("INSERT INTO blog_authors (name) VALUES (?)")->execute([$name]);
    return (int) $db->lastInsertId();
}

function blog_slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}
