<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$allowed = ['en', 'fr', 'rw', 'sw'];
$lang    = $_GET['lang'] ?? $_POST['lang'] ?? 'en';

if (in_array($lang, $allowed, true)) {
    $_SESSION['lang'] = $lang;
}

$back = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: ' . $back);
exit;
