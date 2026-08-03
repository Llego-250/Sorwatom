<?php
/* Language bootstrap — include ONCE before any output.
   Sets $LANG_CODE and loads the translation array into $LANG. */

if (session_status() === PHP_SESSION_NONE) session_start();

$LANG_CODE = 'en';
$allowed   = ['en', 'fr', 'rw', 'sw'];

if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $allowed, true)) {
    $LANG_CODE = $_SESSION['lang'];
}

$lang_file = __DIR__ . '/../lang/' . $LANG_CODE . '.php';
$LANG = file_exists($lang_file) ? require $lang_file : require __DIR__ . '/../lang/en.php';

/**
 * Translate a dot-notation key.  e.g. __('nav.home')
 * Supports {placeholder} replacements.
 */
function __t(string $key, array $replace = []): string {
    global $LANG;
    $parts = explode('.', $key);
    $val   = $LANG;
    foreach ($parts as $part) {
        if (is_array($val) && isset($val[$part])) {
            $val = $val[$part];
        } else {
            return $key; // key not found — return key so missing strings are obvious
        }
    }
    if (!is_string($val)) return $key;
    foreach ($replace as $k => $v) {
        $val = str_replace('{' . $k . '}', $v, $val);
    }
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

/** Raw translation — no htmlspecialchars (for HTML content). */
function __r(string $key, array $replace = []): string {
    global $LANG;
    $parts = explode('.', $key);
    $val   = $LANG;
    foreach ($parts as $part) {
        if (is_array($val) && isset($val[$part])) {
            $val = $val[$part];
        } else {
            return $key;
        }
    }
    if (!is_string($val)) return $key;
    foreach ($replace as $k => $v) {
        $val = str_replace('{' . $k . '}', $v, $val);
    }
    return $val;
}
