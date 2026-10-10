<?php
// ─── Database ─────────────────────────────────────────────────────────────────
// Fill in your MySQL credentials. Or set environment variables on the server.
define('DB_HOST',    getenv('DB_HOST')    ?: 'sql306.infinityfree.com');
define('DB_NAME',    getenv('DB_NAME')    ?: 'if0_42285005_sorwatom_blog');
define('DB_USER',    getenv('DB_USER')    ?: 'if0_42285005');
define('DB_PASS',    getenv('DB_PASS')    ?: 'Llego12345aze');
define('DB_CHARSET', 'utf8mb4');

// ─── Site ─────────────────────────────────────────────────────────────────────
define('SITE_URL', rtrim(getenv('SITE_URL') ?: 'https://www.sorwatom.com', '/'));
define('SITE_NAME', 'Sorwatom');

// ─── Newsletter ───────────────────────────────────────────────────────────────
// Signs unsubscribe links. Changing it invalidates links in emails already sent.
define('NEWSLETTER_SECRET', getenv('NEWSLETTER_SECRET') ?: 'c595f7086e502fbfdeedfdad9b9b14ebe124aeee7061188135992456c5121e4d');

// ─── Admin ────────────────────────────────────────────────────────────────────
// Password: admin2026
define('ADMIN_PASSWORD_HASH', getenv('ADMIN_PASSWORD_HASH')
    ?: '$2y$12$NufWdP5rLf83TEPLb/XQ/e0IJZy9V5fPykEJt0r3TV1UugdxrcIOa');
define('ADMIN_SESSION_NAME', 'sorwatom_admin');
