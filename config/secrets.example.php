<?php
/**
 * Copy this file to secrets.php and fill it in. Never commit secrets.php.
 *
 * Live site (GoDaddy cPanel): upload it to your home folder, ONE LEVEL ABOVE
 * public_html (next to smtp_config.php), so neither the web server nor a deploy
 * can expose or delete it.
 * Local work: config/secrets.php also works (git ignores it).
 * An environment variable with the same name overrides any value here.
 */
return [
    // MySQL — from cPanel → MySQL Databases. Name and user start with the cPanel
    // username, e.g. 'abc123_sorwatom'. The user needs ALL PRIVILEGES on the database.
    'DB_NAME' => '',
    'DB_USER' => '',
    'DB_PASS' => '',
    // 'DB_HOST' => 'localhost',   // the default; only change it if GoDaddy says so

    // Signs unsubscribe links. Make one with:
    //   php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'
    // Keep the value the old host used, or links in emails already sent stop working.
    'NEWSLETTER_SECRET' => '',

    // Admin password, hashed. Run this, type the password, press Enter:
    //   php -r 'echo password_hash(trim(fgets(STDIN)), PASSWORD_DEFAULT), PHP_EOL;'
    'ADMIN_PASSWORD_HASH' => '',
];
