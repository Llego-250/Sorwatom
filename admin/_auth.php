<?php
require_once __DIR__ . '/../config/app.php';

start_session(ADMIN_SESSION_NAME);

// Signed in, and not left idle past ADMIN_IDLE_TIMEOUT
$admin_idle = time() - ($_SESSION['admin_seen'] ?? 0);
if (empty($_SESSION['admin_authed']) || $admin_idle > ADMIN_IDLE_TIMEOUT) {
    $_SESSION = [];
    header('Location: /admin/login.php');
    exit;
}
$_SESSION['admin_seen'] = time();

// CSRF helpers
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Invalid CSRF token. Go back and try again.');
    }
}
