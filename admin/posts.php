<?php
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../data/blog.php';

$posts = blog_get_all_posts_admin();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>All Posts — Sorwatom Admin</title>
<link rel="stylesheet" href="<?= asset_url('/assets/css/tokens.css') ?>">
<link rel="stylesheet" href="<?= asset_url('/admin/assets/admin.css') ?>">
<script src="<?= asset_url('/admin/assets/admin.js') ?>" defer></script>
</head>
<body class="admin-body">

<?php include __DIR__ . '/_nav.php'; ?>

<main class="admin-main">
  <div class="admin-container">

    <div class="page-header">
      <h1>All Posts <span class="count-badge"><?= count($posts) ?></span></h1>
      <a href="/admin/post-edit.php" class="btn-primary">+ New Post</a>
    </div>

    <?php if (empty($posts)): ?>
    <p class="empty-state">No posts yet. <a href="/admin/post-edit.php">Create the first one →</a></p>
    <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Author</th>
          <th>Status</th>
          <th>Published</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $p): ?>
        <tr>
          <td>
            <a href="/admin/post-edit.php?id=<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></a>
            <div class="row-slug">/blog/<?= htmlspecialchars($p['slug']) ?></div>
          </td>
          <td><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($p['author_name']   ?? '—') ?></td>
          <?php $state = blog_is_scheduled($p) ? 'scheduled' : $p['status']; ?>
          <td><span class="status-badge status-<?= $state ?>"><?= $state ?></span></td>
          <td><?= $p['published_at'] ? date('d M Y', strtotime($p['published_at'])) : '—' ?></td>
          <td class="row-actions">
            <?php if ($state === 'published'): ?>
            <a href="/blog/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="link-muted">View</a>
            <?php endif; ?>
            <a href="/admin/post-edit.php?id=<?= $p['id'] ?>" class="link-muted">Edit</a>
            <form method="post" action="/admin/post-delete.php" style="display:inline"
                  data-confirm="Delete this post permanently?">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button type="submit" class="btn-delete">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

  </div>
</main>
</body>
</html>
