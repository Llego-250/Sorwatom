<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Ensure error output doesn't corrupt JSON response
ini_set('display_errors', '0');
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

function json_out(bool $ok, string $message = ''): never {
    echo json_encode(['ok' => $ok, 'message' => $message]);
    exit;
}

// Honeypot — bots fill hidden fields
if (!empty($_POST['hp_website'])) {
    json_out(true);
}

$email = trim($_POST['email'] ?? '');
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(false, 'Please enter a valid email address.');
}

// 1. Always record subscriber in fallback file
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    @mkdir($dataDir, 0755, true);
}
@file_put_contents($dataDir . '/subscribers.txt', date('Y-m-d H:i:s') . ' | ' . $email . PHP_EOL, FILE_APPEND);

// 2. Insert into MySQL database if available
try {
    require_once __DIR__ . '/config/database.php';
    $db = get_db();
    if ($db) {
        $db->exec("CREATE TABLE IF NOT EXISTS newsletter_subscribers (
            id           INT AUTO_INCREMENT PRIMARY KEY,
            email        VARCHAR(255) NOT NULL,
            subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            status       ENUM('active','unsubscribed') DEFAULT 'active',
            UNIQUE KEY uk_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $stmt = $db->prepare(
            "INSERT INTO newsletter_subscribers (email) VALUES (?)
             ON DUPLICATE KEY UPDATE status = 'active'"
        );
        $stmt->execute([$email]);
    }
} catch (Throwable $e) {
    // Database error — subscriber is safely saved in subscribers.txt file
}

json_out(true, "You're on the list — welcome!");
