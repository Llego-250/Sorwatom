<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/data/blog.php';

$base = rtrim(SITE_URL, '/');

// Gather published blog posts
$posts = blog_get_all_posts_admin();

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <!-- Static pages -->
  <url><loc><?= $base ?>/</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc><?= $base ?>/about</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/products</loc><changefreq>monthly</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/product-ketchup</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/product-tomato-paste</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/product-masala</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/product-vinegar</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/blog</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/contact</loc><changefreq>yearly</changefreq><priority>0.6</priority></url>

<?php foreach ($posts as $post):
  if ($post['status'] !== 'published' || blog_is_scheduled($post)) continue;
  $loc  = htmlspecialchars($base . '/blog/' . $post['slug']);
  $mod  = htmlspecialchars(substr($post['updated_at'] ?? $post['published_at'], 0, 10));
  $pub  = htmlspecialchars(substr($post['published_at'], 0, 10));
?>
  <url>
    <loc><?= $loc ?></loc>
    <lastmod><?= $mod ?: $pub ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
<?php endforeach; ?>

</urlset>
