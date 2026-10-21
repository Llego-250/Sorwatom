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
// English fills any key a translation hasn't caught up with yet
$LANG_FALLBACK = $LANG_CODE === 'en' ? $LANG : require __DIR__ . '/../lang/en.php';

/** Resolve a dot-notation key in the current language, then in English. */
function __lookup(string $key): ?string {
    global $LANG, $LANG_FALLBACK;
    foreach ([$LANG, $LANG_FALLBACK] as $source) {
        $val = $source;
        foreach (explode('.', $key) as $part) {
            if (!is_array($val) || !isset($val[$part])) { $val = null; break; }
            $val = $val[$part];
        }
        if (is_string($val)) return $val;
    }
    return null;
}

/**
 * Translate a dot-notation key.  e.g. __('nav.home')
 * Supports {placeholder} replacements.
 */
function __t(string $key, array $replace = []): string {
    $val = __lookup($key);
    if ($val === null) return $key; // key not found — return key so missing strings are obvious
    foreach ($replace as $k => $v) {
        $val = str_replace('{' . $k . '}', $v, $val);
    }
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

/** Raw translation — no htmlspecialchars (for HTML content). */
function __r(string $key, array $replace = []): string {
    $val = __lookup($key);
    if ($val === null) return $key;
    foreach ($replace as $k => $v) {
        $val = str_replace('{' . $k . '}', $v, $val);
    }
    return $val;
}
