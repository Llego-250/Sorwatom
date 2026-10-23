<?php
// ─── Time zone ────────────────────────────────────────────────────────────────
// Kigali time for everything: dates typed in the admin, "published now",
// scheduled posts, emails and logs. The database session follows it (database.php).
date_default_timezone_set('Africa/Kigali');

// ─── Secrets ──────────────────────────────────────────────────────────────────
// Passwords and keys never go in this file — the repository is public. Each one
// comes from an environment variable or from secrets.php, which sits one level
// above the web root (next to smtp_config.php) or, for local work, in this folder.
// See config/secrets.example.php.
$app_secrets = (static function (): array {
    foreach ([dirname(__DIR__, 2) . '/secrets.php', __DIR__ . '/secrets.php'] as $file) {
        if (is_file($file)) return (array) require $file;
    }
    return [];
})();
$app_secret = static fn(string $key, string $default = ''): string
    => (string) (getenv($key) ?: ($app_secrets[$key] ?? $default));

// ─── Database ─────────────────────────────────────────────────────────────────
define('DB_HOST',    $app_secret('DB_HOST', 'sql306.infinityfree.com'));
define('DB_NAME',    $app_secret('DB_NAME', 'if0_42285005_sorwatom_blog'));
define('DB_USER',    $app_secret('DB_USER', 'if0_42285005'));
define('DB_PASS',    $app_secret('DB_PASS'));
define('DB_CHARSET', 'utf8mb4');

// ─── Site ─────────────────────────────────────────────────────────────────────
define('SITE_URL', rtrim(getenv('SITE_URL') ?: 'https://www.sorwatom.com', '/'));
define('SITE_NAME', 'Sorwatom');

// ─── Newsletter ───────────────────────────────────────────────────────────────
// Signs unsubscribe links. Changing it invalidates links in emails already sent.
define('NEWSLETTER_SECRET', $app_secret('NEWSLETTER_SECRET'));

// ─── Admin ────────────────────────────────────────────────────────────────────
// A password_hash() of the admin password; with none set, nobody can sign in.
define('ADMIN_PASSWORD_HASH', $app_secret('ADMIN_PASSWORD_HASH'));
define('ADMIN_SESSION_NAME', 'sorwatom_admin');
define('ADMIN_IDLE_TIMEOUT', 2 * 60 * 60); // seconds without a request before sign-out

unset($app_secrets, $app_secret);

// ─── Sessions ─────────────────────────────────────────────────────────────────
/** True when the visitor reached the site over HTTPS, directly or through the host's proxy. */
function request_is_https(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
}

/** Start a session whose cookie page scripts can't read and other sites can't send along. */
function start_session(?string $name = null): void {
    if (session_status() !== PHP_SESSION_NONE) return;
    if ($name !== null) session_name($name);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => request_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ─── Rate limits ──────────────────────────────────────────────────────────────
/**
 * How many $bucket events (a failed sign-in, a contact message…) this visitor's
 * IP address had in the last $window seconds, optionally recording one more first.
 * Kept in data/rate-limits.json; nothing older than a day is stored.
 */
function rate_limit_count(string $bucket, int $window, bool $record = false): int {
    $fh = @fopen(__DIR__ . '/../data/rate-limits.json', 'c+');
    if (!$fh) return 0;
    flock($fh, LOCK_EX);
    $all = json_decode(stream_get_contents($fh) ?: '[]', true) ?: [];
    $key = $bucket . '|' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $now = time();
    foreach ($all as $k => $times) {
        $all[$k] = array_values(array_filter((array) $times, fn($t) => $t > $now - 86400));
        if (!$all[$k]) unset($all[$k]);
    }
    if ($record) $all[$key][] = $now;
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode($all));
    flock($fh, LOCK_UN);
    fclose($fh);
    return count(array_filter($all[$key] ?? [], fn($t) => $t > $now - $window));
}
