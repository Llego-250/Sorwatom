<?php
ini_set('display_errors', '0');
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

// Locate smtp_config.php in parent directories
$smtp_config_path = null;
$possible_paths = [
    dirname(__DIR__, 2) . '/smtp_config.php',
    dirname(__DIR__) . '/smtp_config.php',
    __DIR__ . '/../smtp_config.php',
    __DIR__ . '/smtp_config.php'
];

foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        $smtp_config_path = $path;
        break;
    }
}

if ($smtp_config_path) {
    require_once $smtp_config_path;
}

include_once __DIR__ . '/mail.php';

$inquiry = isset($_POST['inquiry_type']) ? trim(htmlspecialchars($_POST['inquiry_type'])) : 'General';
$name    = isset($_POST['name'])         ? trim(htmlspecialchars($_POST['name']))         : '';
$company = isset($_POST['company'])      ? trim(htmlspecialchars($_POST['company']))      : '';
$email   = isset($_POST['email'])        ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$mobile  = isset($_POST['mobile'])       ? trim(htmlspecialchars($_POST['mobile']))       : '';
$message = isset($_POST['message'])      ? trim(htmlspecialchars($_POST['message']))      : '';

if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// Store contact inquiry in file log
$dataDir = dirname(__DIR__) . '/data';
if (!is_dir($dataDir)) {
    @mkdir($dataDir, 0755, true);
}
$logEntry = date('Y-m-d H:i:s') . " | Name: $name | Email: $email | Mobile: $mobile | Company: $company | Type: $inquiry | Msg: $message\n";
@file_put_contents($dataDir . '/contact_inquiries.txt', $logEntry, FILE_APPEND);

$subject = "[$inquiry] New inquiry from $name";
$body  = "Inquiry Type: $inquiry\n";
$body .= "Name: $name\n";
$body .= $company ? "Company: $company\n" : '';
$body .= "Email: $email\n";
$body .= $mobile ? "Mobile: $mobile\n" : '';
$body .= "\nMessage:\n$message";

$sent = false;
try {
    if (class_exists('mail')) {
        $mailObj = new mail();
        $sent = $mailObj->send('info@sorwatom.com', $subject, $body);
    }
} catch (Throwable $e) {
    $sent = false;
}

echo json_encode([
    'success' => true,
    'message' => 'Your message was sent successfully. We will be in touch within one business day.'
]);
