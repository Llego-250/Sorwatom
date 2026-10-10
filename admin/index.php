<?php
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../data/blog.php';
require_once __DIR__ . '/../data/newsletter.php';

$posts      = blog_get_all_posts_admin();
$total      = count($posts);
$published  = count(array_filter($posts, fn($p) => $p['status'] === 'published'));
$drafts     = $total - $published;
$categories = blog_get_all_categories_admin();
$authors    = blog_get_authors();
$subscribers = newsletter_active_count();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard — Sorwatom Admin</title>
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/_nav.php'; ?>

<main class="admin-main">
  <div class="admin-container">

    <div class="page-header">
      <h1>Dashboard</h1>
      <a href="/admin/post-edit.php" class="btn-primary">+ New Post</a>
    </div>

    <div class="stats-row">
      <div class="stat-card">
        <span class="stat-value"><?= $total ?></span>
        <span class="stat-label">Total Posts</span>
      </div>
      <div class="stat-card">
        <span class="stat-value"><?= $published ?></span>
        <span class="stat-label">Published</span>
      </div>
      <div class="stat-card">
        <span class="stat-value"><?= $drafts ?></span>
        <span class="stat-label">Drafts</span>
      </div>
      <div class="stat-card">
        <span class="stat-value"><?= count($categories) ?></span>
        <span class="stat-label">Categories</span>
      </div>
      <a href="/admin/subscribers.php" class="stat-card stat-card--link">
        <span class="stat-value"><?= $subscribers ?></span>
        <span class="stat-label">Subscribers</span>
      </a>
    </div>

    <div class="section-title">Recent Posts</div>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Author</th>
          <th>Status</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_slice($posts, 0, 10) as $p): ?>
        <tr>
          <td><a href="/admin/post-edit.php?id=<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></a></td>
          <td><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($p['author_name']   ?? '—') ?></td>
          <td><span class="status-badge status-<?= $p['status'] ?>"><?= $p['status'] ?></span></td>
          <td><?= $p['published_at'] ? date('d M Y', strtotime($p['published_at'])) : '—' ?></td>
          <td class="row-actions">
            <a href="/blog/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="link-muted">View</a>
            <a href="/admin/post-edit.php?id=<?= $p['id'] ?>" class="link-muted">Edit</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if ($total > 10): ?>
    <p style="margin-top:.75rem"><a href="/admin/posts.php">View all <?= $total ?> posts →</a></p>
    <?php endif; ?>

  </div>
</main>
</body>
</html>
