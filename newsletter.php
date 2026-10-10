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
    json_out(false, 'Please enter a valid email address.');
}

// Saved to the database, with data/subscribers.txt as a backup. Someone already
// on the list gets no second welcome email (the form can't be used to spam an inbox).
if (!newsletter_subscribe($email)) {
    json_out(true, "You're already subscribed.", ['already' => true]);
}
try {
    newsletter_send_welcome($email);
} catch (Throwable $e) {
    // The subscription itself succeeded; a missed welcome email isn't worth an error.
}

json_out(true, "You're on the list — welcome!");
