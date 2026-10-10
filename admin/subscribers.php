<?php
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../data/newsletter.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $email = newsletter_normalize_email((string) ($_POST['email'] ?? ''));
    if ($email && newsletter_unsubscribe($email)) {
        $_SESSION['nl_flash'] = ['success', "$email has been unsubscribed and won't receive any more emails."];
    }
    header('Location: /admin/subscribers.php');
    exit;
}

$flash = $_SESSION['nl_flash'] ?? null;
unset($_SESSION['nl_flash']);

$subscribers  = newsletter_list_subscribers();
$active       = count(array_filter($subscribers, fn($s) => $s['status'] === 'active'));
$unsubscribed = count($subscribers) - $active;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Subscribers — Sorwatom Admin</title>
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/_nav.php'; ?>

<main class="admin-main">
  <div class="admin-container">

    <div class="page-header">
      <h1>Subscribers <span class="count-badge"><?= $active ?></span></h1>
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash[0] === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($flash[1]) ?></div>
    <?php endif; ?>

    <div class="stats-row">
      <div class="stat-card">
        <span class="stat-value"><?= $active ?></span>
        <span class="stat-label">Active</span>
      </div>
      <div class="stat-card">
        <span class="stat-value"><?= $unsubscribed ?></span>
        <span class="stat-label">Unsubscribed</span>
      </div>
    </div>

    <p class="field-hint" style="margin-bottom:1rem">
      People join from the newsletter form in the site footer and the welcome popup. To email a post,
      open it in <a href="/admin/posts.php">Posts</a> and use the Newsletter panel.
    </p>

    <?php if (empty($subscribers)): ?>
    <p class="empty-state">No subscribers yet.</p>
    <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Email</th>
          <th>Subscribed</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($subscribers as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['email']) ?></td>
          <td><?= $s['subscribed_at'] ? date('d M Y', strtotime($s['subscribed_at'])) : '—' ?></td>
          <td><span class="status-badge status-<?= $s['status'] ?>"><?= $s['status'] ?></span></td>
          <td style="text-align:right">
            <?php if ($s['status'] === 'active'): ?>
            <form method="post" style="display:inline"
                  onsubmit="return confirm('Unsubscribe this address? They will stop receiving emails.')">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
              <input type="hidden" name="email" value="<?= htmlspecialchars($s['email']) ?>">
              <button type="submit" class="btn-delete">Unsubscribe</button>
            </form>
            <?php endif; ?>
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
