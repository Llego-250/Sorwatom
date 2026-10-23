<?php
/**
 * Copy this file to secrets.php and fill it in. Never commit secrets.php.
 *
 * Live site: upload it ONE LEVEL ABOVE the web root (next to smtp_config.php,
 * outside htdocs/), so neither the web server nor a deploy can expose or delete it.
 * Local work: config/secrets.php also works (git ignores it).
 * An environment variable with the same name overrides any value here.
 */
return [
    // MySQL password (on InfinityFree: the hosting account password)
    'DB_PASS' => '',

    // Signs unsubscribe links. Make one with:
    //   php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'
    'NEWSLETTER_SECRET' => '',

    // Admin password, hashed. Run this, type the password, press Enter:
    //   php -r 'echo password_hash(trim(fgets(STDIN)), PASSWORD_DEFAULT), PHP_EOL;'
    'ADMIN_PASSWORD_HASH' => '',

    // Optional overrides of the defaults in config/app.php
    // 'DB_HOST' => '',
    // 'DB_NAME' => '',
    // 'DB_USER' => '',
];
