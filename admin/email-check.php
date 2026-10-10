<?php
/**
 * Email check — shows whether this server can send email and, if not, why.
 * Used by the contact form, welcome emails and the newsletter alike.
 */
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../mailer_contact/mail.php';

$loaded   = array_values(array_filter(get_included_files(), fn($f) => basename($f) === 'smtp_config.php'));
$expected = realpath(dirname(__DIR__, 2)) . '/smtp_config.php';
$host     = defined('SMTP_HOST') ? (string) SMTP_HOST : '';
$port     = defined('SMTP_PORT') ? (int) SMTP_PORT : 0;
$from     = defined('SMTP_EMAIL') ? SMTP_EMAIL : '';
$user     = defined('SMTP_USERNAME') ? SMTP_USERNAME : $from;
$password = defined('SMTP_PASSWORD') ? (string) SMTP_PASSWORD : '';
$is_gmail = (bool) preg_match('/gmail|google/i', $host);

// Can this server open a connection to the SMTP server at all?
$reach = null;
if ($host !== '' && $port) {
    $errno  = 0;
    $errstr = '';
    $start  = microtime(true);
    $sock   = @fsockopen((smtp_encryption() === 'ssl' ? 'ssl://' : '') . $host, $port, $errno, $errstr, 10);
    $reach  = $sock
        ? ['ok' => true, 'msg' => sprintf('Connected in %.1fs.', microtime(true) - $start)]
        : ['ok' => false, 'msg' => trim("Could not connect: $errstr (error $errno)")];
    if ($sock) fclose($sock);
}

// Test send, with the SMTP conversation. Credentials and message content are hidden.
$test = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $to = filter_var(trim($_POST['to'] ?? ''), FILTER_VALIDATE_EMAIL);
    if ($to) {
        $_SESSION['nl_test_email'] = $to;
        $mailer = new mail();
        $ok = $mailer->send($to, 'Sorwatom email check', 'If you can read this, the website can send email.', '', ['debug' => true]);
        $test = ['ok' => $ok, 'to' => $to, 'error' => trim($mailer->error), 'log' => email_check_redact($mailer->log)];
    } else {
        $test = ['ok' => false, 'to' => '', 'error' => 'Enter a valid email address.', 'log' => []];
    }
}

// Plain-language next step for the usual failures.
function email_check_hint(string $error): string {
    if (stripos($error, 'not configured') !== false)
        return 'Create smtp_config.php using the template at the bottom of this page.';
    if (preg_match('/\b535\b|534|Username and Password not accepted|BadCredentials|credentials invalid|authenticat/i', $error))
        return 'The SMTP server rejected the login. For Gmail, the password must be a 16-character App Password (Google Account → Security → 2-Step Verification → App passwords), and SMTP_USERNAME must be the full Gmail address.';
    if (preg_match('/refused|timed? ?out|unreachable|Failed to connect|Could not connect|Connection failed|getaddrinfo/i', $error))
        return 'This server could not open a connection to the SMTP server. Check SMTP_HOST, then try the other port (465 ↔ 587). If neither connects, the host is blocking outgoing mail and an HTTP-based email service is needed.';
    if (preg_match('/EHLO|STARTTLS|SSL|TLS|certificate/i', $error))
        return 'The secure connection failed. Use port 465 or 587 — the encryption is picked automatically from the port.';
    return '';
}

function email_check_redact(array $lines): array {
    $out    = [];
    $hidden = false;
    foreach ($lines as $line) {
        if (preg_match('/^CLIENT -> SERVER: (.*)$/', $line, $m)) {
            $cmd = $m[1];
            if (preg_match('/^(EHLO|HELO|STARTTLS|MAIL FROM|RCPT TO|DATA|QUIT|RSET|NOOP)\b/i', $cmd)) {
                $out[] = $line;
                $hidden = false;
            } elseif (preg_match('/^AUTH (\S+)/i', $cmd, $a)) {
                $out[] = 'CLIENT -> SERVER: AUTH ' . $a[1] . ' [credentials hidden]';
                $hidden = true;
            } elseif (!$hidden) {
                $out[] = 'CLIENT -> SERVER: [hidden]';
                $hidden = true;
            }
            continue;
        }
        $out[] = $line;
        $hidden = false;
    }
    return $out;
}

$row = fn($label, $value, $state = '') =>
    '<tr><th>' . $label . '</th><td' . ($state ? ' class="ec-' . $state . '"' : '') . '>' . $value . '</td></tr>';
$h = fn($s) => htmlspecialchars((string) $s);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Email Check — Sorwatom Admin</title>
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/_nav.php'; ?>

<main class="admin-main">
  <div class="admin-container">

    <div class="page-header">
      <h1>Email Check</h1>
    </div>

    <p class="field-hint" style="margin-bottom:1.25rem">
      The contact form, welcome emails and newsletter all send through the SMTP account in
      <code>smtp_config.php</code>. This page shows whether that works on this server.
    </p>

    <div class="section-title">1. Settings</div>
    <table class="admin-table ec-table">
      <?php if ($loaded): ?>
        <?= $row('smtp_config.php', 'Found at <code>' . $h($loaded[0]) . '</code>', 'ok') ?>
      <?php else: ?>
        <?= $row('smtp_config.php', 'Not found. Create it at <code>' . $h($expected) . '</code> (see below).', 'bad') ?>
      <?php endif; ?>
      <?= $row('Server', $host !== '' ? $h($host) : 'missing', $host !== '' ? '' : 'bad') ?>
      <?= $row('Port / encryption', $port ? $port . ' / ' . (smtp_encryption() === 'ssl' ? 'SSL (direct TLS)' : 'STARTTLS') : 'missing', $port ? '' : 'bad') ?>
      <?= $row('Sends as', $from !== '' ? $h($from) : 'missing', $from !== '' ? '' : 'bad') ?>
      <?= $row('Login', $user !== '' ? $h($user) : 'missing', $user !== '' ? '' : 'bad') ?>
      <?php
        $pw_len  = strlen(str_replace(' ', '', $password));
        $pw_note = $password === '' ? 'missing' : "set ($pw_len characters)";
        if ($is_gmail && $password !== '' && $pw_len !== 16) $pw_note .= ' — Gmail needs a 16-character App Password, not your normal password';
      ?>
      <?= $row('Password', $pw_note, $password === '' || ($is_gmail && $pw_len !== 16) ? 'bad' : '') ?>
      <?= $row('PHP mail() fallback', function_exists('mail') ? 'available' : 'disabled on this server (normal on InfinityFree), so SMTP must work') ?>
    </table>

    <?php if ($reach): ?>
    <div class="section-title">2. Connection</div>
    <table class="admin-table ec-table">
      <?= $row('Reach ' . $h($host) . ':' . $port, $h($reach['msg']), $reach['ok'] ? 'ok' : 'bad') ?>
    </table>
    <?php endif; ?>

    <div class="section-title"><?= $reach ? '3' : '2' ?>. Send a test email</div>
    <form method="post" class="ec-form">
      <input type="hidden" name="csrf_token" value="<?= $h(csrf_token()) ?>">
      <input type="email" name="to" required placeholder="you@example.com" value="<?= $h($_SESSION['nl_test_email'] ?? '') ?>">
      <button type="submit" class="btn-primary">Send test</button>
    </form>

    <?php if ($test): ?>
    <div class="alert alert-<?= $test['ok'] ? 'success' : 'error' ?>">
      <?= $test['ok']
          ? 'Sent to ' . $h($test['to']) . '. If it doesn’t arrive within a few minutes, check the spam folder.'
          : 'Not sent. ' . $h($test['error']) ?>
      <?php if (!$test['ok'] && ($hint = email_check_hint($test['error']))): ?>
      <br><strong>What to do:</strong> <?= $h($hint) ?>
      <?php endif; ?>
    </div>
    <?php if ($test['log']): ?>
    <details class="ec-log" <?= $test['ok'] ? '' : 'open' ?>>
      <summary>SMTP conversation</summary>
      <pre><?= $h(implode("\n", $test['log'])) ?></pre>
    </details>
    <?php endif; ?>
    <?php endif; ?>

    <div class="section-title">smtp_config.php template</div>
    <p class="field-hint" style="margin-bottom:.6rem">
      Put this file one folder <strong>above</strong> the website files (next to <code>htdocs</code>, not inside it),
      so it can never be downloaded. For Gmail, turn on 2-Step Verification and create an App Password at
      myaccount.google.com/apppasswords. Use that 16-character password, not your normal one.
    </p>
    <pre class="ec-code">&lt;?php
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_PORT',     465);                    // 465 = SSL, 587 = STARTTLS
define('SMTP_EMAIL',    'you@gmail.com');        // the address emails come from
define('SMTP_USERNAME', 'you@gmail.com');
define('SMTP_PASSWORD', 'abcdabcdabcdabcd');     // Gmail App Password</pre>

  </div>
</main>
</body>
</html>
