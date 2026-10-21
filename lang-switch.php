<?php
/* Language switch — /lang-switch?lang=rw&to=/about
   Saves the choice in the session, then sends the visitor back to the page they were on. */
require_once __DIR__ . '/partials/_lang.php';

$lang = (string) ($_GET['lang'] ?? '');
if (in_array($lang, $allowed, true)) {
    $_SESSION['lang'] = $lang;
}

// Same-site paths only — never "//host" or "/\host", which browsers treat as another site
$to = (string) ($_GET['to'] ?? '/');
if (!preg_match('#^/(?![/\\\\])[^\x00-\x1F\x7F]*$#', $to)) {
    $to = '/';
}

header('Location: ' . $to, true, 302);
exit;
