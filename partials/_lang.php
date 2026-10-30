<?php
/* Language bootstrap — include ONCE before any output.
   Sets $LANG_CODE and loads the translation array into $LANG. */

// Site settings (time zone) apply to every page that loads the language
require_once __DIR__ . '/../config/app.php';

start_session();

$LANG_CODE = 'en';
$allowed   = ['en', 'rw'];

// ?lang= in the address picks the language (and remembers it), so each language has
// its own URL that search engines can index — English plain, Kinyarwanda ?lang=rw.
if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}

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

/**
 * A hero title (raw translation) with its highlighted <em> word made a link,
 * e.g. to the section the page leads into. External links open in a new tab.
 */
function hero_title(string $key, string $href): string {
    $attrs = 'href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" class="hero__title-link"';
    if (preg_match('~^https?://~', $href)) $attrs .= ' target="_blank" rel="noopener noreferrer"';
    return preg_replace('~<em>(.*?)</em>~s', '<a ' . $attrs . '><em>$1</em></a>', __r($key), 1);
}

/** Date as "21 October 2026", with the month name in the current language. */
function __date(int $ts): string {
    $months = explode(',', __lookup('blog.months') ?? '');
    return date('j', $ts) . ' ' . ($months[(int) date('n', $ts) - 1] ?? date('F', $ts)) . ' ' . date('Y', $ts);
}
