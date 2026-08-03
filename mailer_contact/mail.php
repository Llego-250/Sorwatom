<?php
// Locate smtp_config.php if not already loaded
if (!defined('SMTP_HOST')) {
    $possible_paths = [
        dirname(__DIR__, 2) . '/smtp_config.php',
        dirname(__DIR__) . '/smtp_config.php',
        __DIR__ . '/../smtp_config.php',
        __DIR__ . '/smtp_config.php'
    ];
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
}

class mail {
    function send($email, $subject, $body, $header = '') {
        $phpMailerPath = __DIR__ . '/mailer/PHPMailerAutoload.php';
        if (file_exists($phpMailerPath)) {
            require_once $phpMailerPath;
        }

        if (class_exists('PHPMailer') && defined('SMTP_HOST')) {
            try {
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Timeout    = 3; // 3s timeout
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth   = true;
                $mail->Host       = SMTP_HOST;
                $mail->Port       = SMTP_PORT;
                $mail->Username   = defined('SMTP_USERNAME') ? SMTP_USERNAME : (defined('SMTP_EMAIL') ? SMTP_EMAIL : '');
                $mail->Password   = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
                $mail->setFrom(defined('SMTP_EMAIL') ? SMTP_EMAIL : 'solideaze@gmail.com');
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->Body    = $body;

                if (@$mail->send()) {
                    return true;
                }
            } catch (Throwable $e) {
                // fall through to mail() fallback
            }
        }

        $from = defined('SMTP_EMAIL') ? SMTP_EMAIL : 'solideaze@gmail.com';
        return @mail($email, $subject, $body, 'From: ' . $from);
    }
}

$mail = new mail();
