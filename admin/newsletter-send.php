<?php
/**
 * Emails a published post to newsletter subscribers.
 *
 * POST action=queue  → queue the post for everyone who hasn't had it, then show progress
 * POST action=batch  → (AJAX) send the next few queued emails, return JSON status
 * POST action=test   → send one test copy to an address of your choice
 * GET  ?post=ID      → progress page, which drives the batches
 */
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../data/newsletter.php';

$post_id = (int) ($_POST['post'] ?? $_GET['post'] ?? 0);
$post    = $post_id ? newsletter_get_post($post_id) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';

    if ($action === 'batch') {
        // Release the session lock so other admin tabs stay usable while this sends.
        session_write_close();
        @set_time_limit(60);
        header('Content-Type: application/json; charset=utf-8');
        try {
            if (!$post) throw new RuntimeException('Only published posts can be emailed.');
            echo json_encode(['ok' => true] + newsletter_send_batch($post_id));
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    if (!$post) {
        $_SESSION['nl_flash'] = ['error', 'Only published posts can be emailed. Publish and save the post first.'];
        header('Location: /admin/post-edit.php?id=' . $post_id);
        exit;
    }

    if ($action === 'test') {
        $to = newsletter_normalize_email((string) ($_POST['test_email'] ?? ''));
        $ok = false;
        if ($to) {
            $_SESSION['nl_test_email'] = $to;
            try {
                $ok = newsletter_send_post_to(newsletter_post_view($post), $to, new mail(), '[Test] ' . $post['title']);
            } catch (Throwable $e) {
                $ok = false;
            }
        }
        $_SESSION['nl_flash'] = $ok
            ? ['success', "Test email sent to $to. Check the inbox (and spam folder)."]
            : ['error', $to ? "The test email to $to could not be sent. Check the SMTP settings in smtp_config.php." : 'Enter a valid email address for the test.'];
        header('Location: /admin/post-edit.php?id=' . $post_id);
        exit;
    }

    if ($action === 'queue') {
        newsletter_queue_post($post_id);
        header('Location: /admin/newsletter-send.php?post=' . $post_id);
        exit;
    }

    http_response_code(400);
    exit('Unknown action.');
}

if (!$post) {
    header('Location: /admin/posts.php');
    exit;
}

$status = newsletter_post_status($post_id);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Email to Subscribers — Sorwatom Admin</title>
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/_nav.php'; ?>

<main class="admin-main">
  <div class="admin-container">

    <div class="page-header">
      <h1>Email to Subscribers</h1>
      <a href="/admin/post-edit.php?id=<?= $post_id ?>" class="btn-ghost">← Back to post</a>
    </div>

    <div class="nl-progress-card" id="nl-progress"
         data-post="<?= $post_id ?>"
         data-csrf="<?= htmlspecialchars(csrf_token()) ?>"
         data-status="<?= htmlspecialchars(json_encode($status)) ?>">

      <p class="nl-post-title"><?= htmlspecialchars($post['title']) ?></p>

      <div class="nl-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
        <div class="nl-bar__fill" id="nl-bar-fill"></div>
      </div>

      <div class="nl-counts">
        <div><span class="stat-value" id="nl-sent">0</span><span class="stat-label">Sent</span></div>
        <div><span class="stat-value" id="nl-pending">0</span><span class="stat-label">Waiting</span></div>
        <div><span class="stat-value nl-failed" id="nl-failed">0</span><span class="stat-label">Failed</span></div>
      </div>

      <p class="nl-message" id="nl-message" role="status" aria-live="polite"></p>

      <div class="nl-actions">
        <button type="button" class="btn-primary" id="nl-resume" hidden>Resume sending</button>
        <form method="post" action="/admin/newsletter-send.php" id="nl-retry" hidden>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
          <input type="hidden" name="action" value="queue">
          <input type="hidden" name="post" value="<?= $post_id ?>">
          <button type="submit" class="btn-primary">Retry failed emails</button>
        </form>
      </div>

      <p class="field-hint">Emails go out a few at a time. Keep this page open until it says “Done”. If you close it, sending pauses and you can resume from the post’s Newsletter panel.</p>
    </div>

  </div>
</main>

<script>
(function () {
  const card   = document.getElementById('nl-progress');
  const el     = id => document.getElementById(id);
  const resume = el('nl-resume');
  const retry  = el('nl-retry');
  let running  = false;

  function render(s) {
    const waiting = s.pending + s.sending;
    const total   = s.sent + s.failed + waiting;
    const pct     = total ? Math.round((s.sent + s.failed) / total * 100) : 100;
    el('nl-sent').textContent    = s.sent;
    el('nl-pending').textContent = waiting;
    el('nl-failed').textContent  = s.failed;
    el('nl-bar-fill').style.width = pct + '%';
    card.querySelector('.nl-bar').setAttribute('aria-valuenow', pct);
    retry.hidden = running || waiting > 0 || s.failed === 0;
  }

  function message(text, type) {
    const m = el('nl-message');
    m.textContent = text;
    m.className = 'nl-message' + (type ? ' nl-message--' + type : '');
  }

  function finish(s) {
    if (s.failed > 0) {
      message(s.failed + ' email(s) failed. Check the SMTP settings, then retry.', 'error');
    } else if (s.sent === 0) {
      message('There are no active subscribers to email yet.', '');
    } else {
      message('Done — this post has been emailed to ' + s.sent + ' subscriber(s).', 'success');
    }
  }

  async function run() {
    running = true;
    resume.hidden = true;
    message('Sending… keep this page open.', '');
    const body = new FormData();
    body.append('csrf_token', card.dataset.csrf);
    body.append('action', 'batch');
    body.append('post', card.dataset.post);

    try {
      while (true) {
        const res  = await fetch('/admin/newsletter-send.php', { method: 'POST', body });
        const data = await res.json().catch(() => ({ ok: false, error: 'Unexpected server response (HTTP ' + res.status + ').' }));
        if (!data.ok) throw new Error(data.error || 'Sending failed.');
        render(data);
        if (data.pending === 0) { running = false; render(data); finish(data); return; }
      }
    } catch (err) {
      running = false;
      message(err.message + ' Sending is paused.', 'error');
      resume.hidden = false;
    }
  }

  resume.addEventListener('click', run);

  const initial = JSON.parse(card.dataset.status);
  render(initial);
  if (initial.pending + initial.sending > 0) run();
  else finish(initial);
})();
</script>
</body>
</html>
