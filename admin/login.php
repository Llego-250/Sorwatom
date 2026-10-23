<?php
require_once __DIR__ . '/../config/app.php';

start_session(ADMIN_SESSION_NAME);

if (!empty($_SESSION['admin_authed'])) {
    header('Location: /admin');
    exit;
}

// After LOGIN_MAX_FAILS wrong passwords from one address, sign-in is refused
// for LOGIN_LOCK_SECONDS, so the password can't be guessed by brute force.
const LOGIN_MAX_FAILS    = 5;
const LOGIN_LOCK_SECONDS = 15 * 60;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw = (string) ($_POST['password'] ?? '');
    if (ADMIN_PASSWORD_HASH === '') {
        $error = 'Sign-in is not set up yet: ADMIN_PASSWORD_HASH is missing from secrets.php.';
    } elseif (rate_limit_count('login', LOGIN_LOCK_SECONDS) >= LOGIN_MAX_FAILS) {
        $error = 'Too many failed attempts. Try again in 15 minutes.';
    } elseif (password_verify($pw, ADMIN_PASSWORD_HASH)) {
        @session_regenerate_id(true);
        $_SESSION['admin_authed'] = true;
        $_SESSION['admin_seen']   = time();
        header('Location: /admin');
        exit;
    } else {
        rate_limit_count('login', LOGIN_LOCK_SECONDS, true);
        sleep(1); // slows guessing further without locking anyone out
        $error = 'Incorrect password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — Sorwatom</title>
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-login-page">
<div class="login-card">
  <img src="/assets/img/logo.png" alt="Sorwatom" class="login-logo" style="filter:none">
  <h1>Admin Panel</h1>
  <?php if ($error): ?>
  <p class="login-error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form method="post" action="/admin/login.php">
    <div class="field">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" autofocus autocomplete="current-password" required>
    </div>
    <button type="submit" class="btn-primary">Sign In</button>
  </form>
</div>
</body>
</html>
