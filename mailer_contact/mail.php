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
    /**
     * $options (all optional):
     *   'alt'       => plain-text body; when set, $body is sent as HTML
     *   'reply_to'  => [address, name]
     *   'from_name' => sender display name
     *   'embed'     => [cid => file path] for images referenced as src="cid:..."
     */
    function send($email, $subject, $body, $header = '', array $options = []) {
        $isHtml   = isset($options['alt']);
        $replyTo  = $options['reply_to'] ?? null;
        $fromName = $options['from_name'] ?? '';

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
                $mail->CharSet    = 'UTF-8';
                $mail->setFrom(defined('SMTP_EMAIL') ? SMTP_EMAIL : 'solideaze@gmail.com', $fromName);
                $mail->addAddress($email);
                if ($replyTo) {
                    $mail->addReplyTo($replyTo[0], $replyTo[1] ?? '');
                }
                $mail->Subject = $subject;
                $mail->Body    = $body;
                if ($isHtml) {
                    $mail->isHTML(true);
                    $mail->AltBody = $options['alt'];
                    // addEmbeddedImage() hits get_magic_quotes_runtime() in this PHPMailer
                    // version, which no longer exists on PHP 8 — embed from a string instead.
                    foreach ($options['embed'] ?? [] as $cid => $path) {
                        if (is_file($path)) {
                            $mail->addStringEmbeddedImage(file_get_contents($path), $cid, basename($path));
                        }
                    }
                }

                if (@$mail->send()) {
                    return true;
                }
            } catch (Throwable $e) {
                // fall through to mail() fallback
            }
        }

        $from    = defined('SMTP_EMAIL') ? SMTP_EMAIL : 'solideaze@gmail.com';
        $headers = ['From: ' . ($fromName ? "$fromName <$from>" : $from)];
        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $replyTo[0];
        }
        if ($isHtml) {
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
        }
        $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        return @mail($email, $subject, $body, implode("\r\n", $headers));
    }
}

$mail = new mail();
