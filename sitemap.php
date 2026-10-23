<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/data/blog.php';

$base  = rtrim(SITE_URL, '/');
$langs = ['en', 'rw'];
// English at the plain address, Kinyarwanda at ?lang=rw (see partials/_lang.php)
$url = static fn(string $path, string $lang): string => $base . $path . ($lang === 'en' ? '' : '?lang=' . $lang);

// [path, change frequency, priority, last modified]
$pages = [
    ['/',                     'weekly',  '1.0', ''],
    ['/products',             'monthly', '0.9', ''],
    ['/product-tomato-paste', 'monthly', '0.9', ''],
    ['/product-ketchup',      'monthly', '0.8', ''],
    ['/product-masala',       'monthly', '0.8', ''],
    ['/product-vinegar',      'monthly', '0.8', ''],
    ['/about',                'monthly', '0.8', ''],
    ['/blog',                 'daily',   '0.7', ''],
    ['/contact',              'yearly',  '0.6', ''],
    ['/privacy',              'yearly',  '0.3', ''],
];

// Published blog posts (scheduled ones join once they go live)
foreach (get_db() ? blog_get_all_posts_admin() : [] as $post) {
    if ($post['status'] !== 'published' || blog_is_scheduled($post)) continue;
    $pages[] = ['/blog/' . $post['slug'], 'monthly', '0.6', substr((string) $post['published_at'], 0, 10)];
}

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach ($pages as [$path, $freq, $priority, $mod]): foreach ($langs as $lang): ?>
  <url>
    <loc><?= htmlspecialchars($url($path, $lang)) ?></loc>
<?php if ($mod): ?>
    <lastmod><?= htmlspecialchars($mod) ?></lastmod>
<?php endif; ?>
    <changefreq><?= $freq ?></changefreq>
    <priority><?= $priority ?></priority>
<?php foreach ($langs as $alt): ?>
    <xhtml:link rel="alternate" hreflang="<?= $alt ?>" href="<?= htmlspecialchars($url($path, $alt)) ?>"/>
<?php endforeach; ?>
    <xhtml:link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($url($path, 'en')) ?>"/>
  </url>
<?php endforeach; endforeach; ?>
</urlset>
