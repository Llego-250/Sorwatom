<?php
require_once __DIR__ . '/data/blog.php';

header('Content-Type: application/rss+xml; charset=UTF-8');
header('X-Robots-Tag: noindex');

$posts = blog_get_posts(1, 20);
$site  = SITE_URL;
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>Sorwatom Journal</title>
    <link><?= $site ?>/blog</link>
    <description>Stories from the kitchen — new recipes, cooking tips and stories from across the region.</description>
    <language>en</language>
    <atom:link href="<?= $site ?>/feed" rel="self" type="application/rss+xml"/>
    <lastBuildDate><?= date('r') ?></lastBuildDate>
<?php foreach ($posts as $p): ?>
    <item>
      <title><?= htmlspecialchars($p['title']) ?></title>
      <link><?= $site ?>/blog/<?= htmlspecialchars($p['slug']) ?></link>
      <guid isPermaLink="true"><?= $site ?>/blog/<?= htmlspecialchars($p['slug']) ?></guid>
      <pubDate><?= date('r', strtotime($p['published_at'])) ?></pubDate>
      <description><?= htmlspecialchars($p['excerpt'] ?? '') ?></description>
      <?php if (!empty($p['category_name'])): ?>
      <category><?= htmlspecialchars($p['category_name']) ?></category>
      <?php endif; ?>
      <content:encoded><![CDATA[<?= $p['content'] ?>]]></content:encoded>
    </item>
<?php endforeach; ?>
  </channel>
</rss>
