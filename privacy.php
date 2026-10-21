<?php
require_once __DIR__ . '/partials/_lang.php';
$page_title       = __r('meta.privacy.title');
$page_description = __r('meta.privacy.desc');
$page_css         = ['pages/contact.css'];
$body_class       = 'page-privacy';
$current_page     = '';
include 'partials/_head.php';
?>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <!-- Hero -->
  <section class="hero hero--half" aria-label="<?= __t('privacy.title') ?>">
    <div class="hero__bg hero__bg--pattern" aria-hidden="true"></div>
    <div class="hero__content">
      <div class="container">
        <span class="eyebrow eyebrow--light"><?= __t('privacy.eyebrow') ?></span>
        <h1 class="hero__title"><?= __t('privacy.title') ?></h1>
        <p class="hero__subtitle"><?= __t('privacy.subtitle') ?></p>
      </div>
    </div>
  </section>

  <!-- Content -->
  <section class="section">
    <div class="container--narrow">

      <p class="privacy-updated"><?= __t('privacy.updated') ?>: <?= __date(time()) ?></p>

      <?php
      $sections = ['s1','s2','s3','s4','s5','s6','s7'];
      foreach ($sections as $s):
      ?>
      <div class="privacy-block">
        <h2><?= __t('privacy.'.$s.'.heading') ?></h2>
        <p><?= __t('privacy.'.$s.'.body') ?></p>
      </div>
      <?php endforeach; ?>

    </div>
  </section>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>

</body>
</html>

<style>
.page-privacy .privacy-updated {
  font-size: var(--step--1);
  color: var(--col-text-muted);
  margin-bottom: var(--space-xl);
  padding-bottom: var(--space-md);
  border-bottom: 1px solid var(--col-border);
}
.page-privacy .privacy-block {
  margin-bottom: var(--space-xl);
}
.page-privacy .privacy-block h2 {
  font-size: var(--step-1);
  margin-bottom: var(--space-sm);
}
.page-privacy .privacy-block p {
  color: var(--col-text-muted);
  line-height: 1.8;
}
</style>
