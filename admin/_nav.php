<header class="admin-header">
  <a href="/admin" class="admin-brand">
    <img src="/assets/img/logo.png" alt="Sorwatom" height="34" style="filter:none">
    <span>Admin</span>
  </a>
  <nav class="admin-nav">
    <a href="/admin" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Dashboard</a>
    <a href="/admin/posts.php" class="<?= basename($_SERVER['PHP_SELF']) === 'posts.php' ? 'active' : '' ?>">Posts</a>
    <a href="/admin/post-edit.php" class="<?= basename($_SERVER['PHP_SELF']) === 'post-edit.php' && empty($_GET['id']) ? 'active' : '' ?>">New Post</a>
    <a href="/admin/subscribers.php" class="<?= basename($_SERVER['PHP_SELF']) === 'subscribers.php' ? 'active' : '' ?>">Subscribers</a>
    <a href="/blog" target="_blank" class="link-muted">View Blog ↗</a>
    <a href="/admin/logout.php" class="link-muted">Sign Out</a>
  </nav>
</header>
