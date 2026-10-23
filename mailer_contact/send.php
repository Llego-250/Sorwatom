<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

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
require_once __DIR__ . '/../partials/_lang.php';
include_once __DIR__ . '/template.php';

// Honeypot — bots fill hidden fields; they get a success reply and nothing is sent
if (!empty($_POST['hp_website'])) {
    echo json_encode(['success' => true, 'message' => __r('contact.form.msg.sent')]);
    exit;
}

// Raw values — escaping happens in the email template. Single-line fields have
// newlines collapsed so they can't break the log format or mail headers.
$field = static fn(string $key): string => isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
$line  = static fn(string $key): string => preg_replace('/\s+/', ' ', $field($key));

$inquiry = $line('inquiry_type') ?: 'General';
$name    = $line('name');
$company = $line('company');
$email   = filter_var($field('email'), FILTER_SANITIZE_EMAIL);
$mobile  = $line('mobile');
$message = $field('message');

if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => __r('contact.form.msg.required')]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => __r('contact.form.msg.email')]);
    exit;
}

// At most 5 messages an hour from one address, so the form can't flood the inbox
if (rate_limit_count('contact', 3600, true) > 5) {
    echo json_encode(['success' => false, 'message' => __r('contact.form.msg.too_many')]);
    exit;
}

// Store contact inquiry in file log
$dataDir = dirname(__DIR__) . '/data';
if (!is_dir($dataDir)) {
    @mkdir($dataDir, 0755, true);
}
$logMessage = preg_replace('/\s+/', ' ', $message);
$logEntry = date('Y-m-d H:i:s') . " | Name: $name | Email: $email | Mobile: $mobile | Company: $company | Type: $inquiry | Msg: $logMessage";

// The privacy policy promises inquiries are deleted after 24 months: drop older
// lines (each starts with its date) whenever a new one is written.
$cutoff = date('Y-m-d H:i:s', strtotime('-24 months'));
if ($fh = @fopen($dataDir . '/contact_inquiries.txt', 'c+')) {
    flock($fh, LOCK_EX);
    $lines = array_filter(
        explode("\n", stream_get_contents($fh)),
        fn($l) => $l !== '' && strncmp($l, $cutoff, 19) >= 0
    );
    $lines[] = $logEntry;
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, implode("\n", $lines) . "\n");
    flock($fh, LOCK_UN);
    fclose($fh);
}

$emailData = [
    'inquiry'  => $inquiry,
    'name'     => $name,
    'company'  => $company,
    'email'    => $email,
    'mobile'   => $mobile,
    'message'  => $message,
    'received' => new DateTime('now', new DateTimeZone('Africa/Kigali')),
];

$subject = 'New ' . contact_inquiry_label($inquiry) . " inquiry from $name" . ($company ? " ($company)" : '');

$sent = false;
try {
    if (class_exists('mail')) {
        $mailObj = new mail();
        $sent = $mailObj->send('solideaze@gmail.com', $subject, contact_email_html($emailData, 'cid:sorwatom-logo'), '', [
            'alt'       => contact_email_text($emailData),
            'reply_to'  => [$email, $name],
            'from_name' => 'Sorwatom Website',
            'embed'     => ['sorwatom-logo' => dirname(__DIR__) . '/assets/img/logo.png'],
        ]);
    }
} catch (Throwable $e) {
    $sent = false;
}

echo json_encode([
    'success' => true,
    'message' => __r('contact.form.msg.sent')
]);
