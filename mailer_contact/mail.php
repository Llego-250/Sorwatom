<?php
// smtp_config.php normally sits one level above the web root (next to htdocs /
// public_html) so the SMTP password can never be downloaded.
function smtp_config_paths(): array {
    return [
        dirname(__DIR__, 2) . '/smtp_config.php',
        dirname(__DIR__) . '/smtp_config.php',
        __DIR__ . '/smtp_config.php',
    ];
}

// Locate smtp_config.php if not already loaded
if (!defined('SMTP_HOST')) {
    foreach (smtp_config_paths() as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
}

// Every failed send is noted here (data/ is not web-accessible) and listed on
// Admin → Email Check, so failures in the contact form or newsletter don't go unseen.
const MAIL_ERROR_LOG = __DIR__ . '/../data/mail-errors.txt';

// Port 465 expects TLS from the first byte; 587/25 start plain and upgrade with
// STARTTLS. SMTP_SECURE in smtp_config.php overrides ('ssl' or 'tls').
function smtp_encryption(): string {
    if (defined('SMTP_SECURE')) return SMTP_SECURE;
    return (int) (defined('SMTP_PORT') ? SMTP_PORT : 587) === 465 ? 'ssl' : 'tls';
}

class mail {
    /** Why the last send() failed, or '' after a success. */
    public $error = '';
    /** SMTP conversation of the last send() when the 'debug' option is on. */
    public $log = [];

    /**
     * $options (all optional):
     *   'alt'       => plain-text body; when set, $body is sent as HTML
     *   'reply_to'  => [address, name]
     *   'from_name' => sender display name
     *   'embed'     => [cid => file path] for images referenced as src="cid:..."
     *   'headers'   => [name => value] extra headers, e.g. List-Unsubscribe
     *   'debug'     => true to record the SMTP conversation in $this->log
     */
    function send($email, $subject, $body, $header = '', array $options = []) {
        $isHtml   = isset($options['alt']);
        $replyTo  = $options['reply_to'] ?? null;
        $fromName = $options['from_name'] ?? '';
        $extra    = $options['headers'] ?? [];
        $this->error = '';
        $this->log   = [];

        $phpMailerPath = __DIR__ . '/mailer/PHPMailerAutoload.php';
        if (file_exists($phpMailerPath)) {
            require_once $phpMailerPath;
        }

        if (!defined('SMTP_HOST') || SMTP_HOST === '') {
            $this->error = 'SMTP is not configured (smtp_config.php not found or SMTP_HOST empty).';
        } elseif (class_exists('PHPMailer')) {
            try {
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Timeout    = 10;
                $mail->SMTPSecure = smtp_encryption();
                $mail->SMTPAuth   = true;
                $mail->Host       = SMTP_HOST;
                $mail->Port       = SMTP_PORT;
                $mail->Username   = defined('SMTP_USERNAME') ? SMTP_USERNAME : (defined('SMTP_EMAIL') ? SMTP_EMAIL : '');
                $mail->Password   = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
                $mail->CharSet    = 'UTF-8';
                // Always listen to the SMTP conversation: PHPMailer's own ErrorInfo is just
                // "SMTP connect() failed", the real reason (bad password, refused...) is in here.
                $debug  = !empty($options['debug']);
                $errors = [];
                $mail->SMTPDebug   = 3;
                $mail->Debugoutput = function ($line) use ($debug, &$errors) {
                    $line = rtrim($line);
                    if ($debug) $this->log[] = $line;
                    if (preg_match('/SMTP ERROR|Connection failed|timed-out|Failed to connect/i', $line)) $errors[] = $line;
                };
                $mail->setFrom(defined('SMTP_EMAIL') ? SMTP_EMAIL : 'solideaze@gmail.com', $fromName);
                $mail->addAddress($email);
                if ($replyTo) {
                    $mail->addReplyTo($replyTo[0], $replyTo[1] ?? '');
                }
                foreach ($extra as $name => $value) {
                    $mail->addCustomHeader($name, $value);
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
                // Prefer the server's own reply ("SMTP ERROR: ... 535 ...") over PHPMailer's summary.
                $server = array_values(array_filter($errors, fn($l) => strpos($l, 'SMTP ERROR') !== false));
                $this->error = 'SMTP: ' . ($server[0] ?? $errors[0] ?? $mail->ErrorInfo);
            } catch (Throwable $e) {
                $this->error = 'SMTP: ' . $e->getMessage();
            }
        }

        // Fallback for hosts with a working mail(). Many (InfinityFree included)
        // disable it, and on PHP 8 a disabled function doesn't exist at all.
        if (!function_exists('mail')) {
            $this->error .= ' PHP mail() is disabled on this server.';
            return $this->failed($email, $subject);
        }
        $from    = defined('SMTP_EMAIL') ? SMTP_EMAIL : 'solideaze@gmail.com';
        $headers = ['From: ' . ($fromName ? "$fromName <$from>" : $from)];
        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $replyTo[0];
        }
        foreach ($extra as $name => $value) {
            $headers[] = "$name: $value";
        }
        if ($isHtml) {
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
        }
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        if (@mail($email, $encodedSubject, $body, implode("\r\n", $headers))) {
            $this->error = '';
            return true;
        }
        $this->error .= ' PHP mail() fallback failed too.';
        return $this->failed($email, $subject);
    }

    private function failed($to, $subject) {
        $line = date('Y-m-d H:i:s') . ' | ' . $to . ' | ' . $subject . ' | ' . trim($this->error);
        @file_put_contents(MAIL_ERROR_LOG, preg_replace('/\s+/', ' ', $line) . PHP_EOL, FILE_APPEND | LOCK_EX);
        return false;
    }
}

$mail = new mail();
