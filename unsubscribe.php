<?php
require_once __DIR__ . '/data/newsletter.php';

// Links in emails carry the address and its signature. Opening the link only asks
// for confirmation — mail scanners that pre-fetch links mustn't unsubscribe anyone.
$src   = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
$email = newsletter_normalize_email((string) ($src['e'] ?? '')) ?? '';
$token = (string) ($src['t'] ?? '');
$state = ($email !== '' && newsletter_token_valid($email, $token)) ? 'confirm' : 'invalid';

if ($state === 'confirm' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $state = newsletter_unsubscribe($email) ? 'done' : 'error';
    } catch (Throwable $e) {
        $state = 'error';
    }
}

$email_html = '<strong>' . htmlspecialchars($email) . '</strong>';

$page_title       = 'Unsubscribe — Sorwatom';
$page_description = 'Manage your Sorwatom newsletter subscription.';
$page_css         = ['pages/contact.css'];
$body_class       = 'page-unsubscribe';
$current_page     = '';
include 'partials/_head.php';
?>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <section class="hero hero--half" aria-label="<?= __t('unsubscribe.title') ?>">
    <div class="hero__bg hero__bg--pattern" aria-hidden="true"></div>
    <div class="hero__content">
      <div class="container">
        <span class="eyebrow eyebrow--light"><?= __t('unsubscribe.eyebrow') ?></span>
        <h1 class="hero__title"><?= __t('unsubscribe.' . $state . '_heading') ?></h1>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container--narrow">
      <div class="unsub-card">

        <?php if ($state === 'confirm'): ?>
        <p><?= __r('unsubscribe.confirm_body', ['email' => $email_html]) ?></p>
        <form method="post" action="/unsubscribe" class="unsub-actions">
          <input type="hidden" name="e" value="<?= htmlspecialchars($email) ?>">
          <input type="hidden" name="t" value="<?= htmlspecialchars($token) ?>">
          <button type="submit" class="btn btn--primary"><?= __t('unsubscribe.confirm_btn') ?></button>
          <a href="/" class="btn btn--ghost-dark"><?= __t('unsubscribe.keep') ?></a>
        </form>

        <?php elseif ($state === 'done'): ?>
        <p><?= __r('unsubscribe.done_body', ['email' => $email_html]) ?></p>
        <div class="unsub-actions">
          <a href="/" class="btn btn--primary"><?= __t('unsubscribe.btn_home') ?></a>
        </div>

        <?php else: ?>
        <p><?= __t('unsubscribe.' . $state . '_body') ?></p>
        <div class="unsub-actions">
          <a href="/" class="btn btn--primary"><?= __t('unsubscribe.btn_home') ?></a>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </section>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>

</body>
</html>

<style>
.page-unsubscribe .unsub-card {
  background: var(--col-surface);
  border: 1px solid var(--col-border);
  border-radius: var(--radius-lg);
  padding: var(--space-lg);
  text-align: center;
}
.page-unsubscribe .unsub-card p {
  font-size: var(--step-1);
  line-height: 1.7;
  color: var(--col-text);
  max-width: 34em;
  margin: 0 auto var(--space-lg);
  overflow-wrap: anywhere;
}
.page-unsubscribe .unsub-actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-sm);
  justify-content: center;
}
</style>
