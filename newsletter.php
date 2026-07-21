<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

function json_out(bool $ok, string $message = ''): never {
    echo json_encode(['ok' => $ok, 'message' => $message]);
    exit;
}

// Honeypot — bots fill hidden fields
if (!empty($_POST['hp_website'])) {
    json_out(true); // silently succeed
}

$email = trim($_POST['email'] ?? '');
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(false, 'Invalid email address.');
}

require_once __DIR__ . '/config/app.php';

try {
    $db = get_db();

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

    json_out(true);
} catch (PDOException $e) {
    json_out(false, 'Server error. Please try again.');
}
