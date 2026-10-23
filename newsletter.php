<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Ensure error output doesn't corrupt JSON response
ini_set('display_errors', '0');
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/data/newsletter.php';
require_once __DIR__ . '/partials/_lang.php';

function json_out(bool $ok, string $message = '', array $extra = []): never {
    echo json_encode(['ok' => $ok, 'message' => $message] + $extra);
    exit;
}

// Honeypot — bots fill hidden fields
if (!empty($_POST['hp_website'])) {
    json_out(true);
}

$email = newsletter_normalize_email((string) ($_POST['email'] ?? ''));
if (!$email) {
    json_out(false, __r('footer.newsletter.error_email'));
}

// Each new address gets a welcome email, so cap sign-ups from one connection
if (rate_limit_count('newsletter', 3600, true) > 5) {
    json_out(false, __r('footer.newsletter.too_many'));
}

// Saved to the database, with data/subscribers.txt as a backup. Someone already
// on the list gets no second welcome email (the form can't be used to spam an inbox).
if (!newsletter_subscribe($email)) {
    json_out(true, __r('footer.newsletter.already'), ['already' => true]);
}
try {
    newsletter_send_welcome($email);
} catch (Throwable $e) {
    // The subscription itself succeeded; a missed welcome email isn't worth an error.
}

json_out(true, __r('footer.newsletter.success'));
