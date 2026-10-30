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
  <section class="hero privacy-hero" aria-label="<?= __t('a11y.page_intro') ?>">
    <div class="hero__bg hero__bg--pattern" aria-hidden="true"></div>
    <svg class="privacy-hero__shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5" stroke-linejoin="round" aria-hidden="true" focusable="false">
      <path d="M12 2.5 4.5 5.4v5.9c0 4.7 3.2 8.9 7.5 10.2 4.3-1.3 7.5-5.5 7.5-10.2V5.4z"/>
      <path d="M12 5 6.8 7v4.5c0 3.4 2.2 6.4 5.2 7.5 3-1.1 5.2-4.1 5.2-7.5V7z"/>
      <rect x="9.6" y="10.6" width="4.8" height="3.8" rx=".6"/>
      <path d="M10.6 10.6V9.4a1.4 1.4 0 0 1 2.8 0v1.2"/>
    </svg>
    <div class="hero__content">
      <div class="container">
        <span class="eyebrow eyebrow--light"><?= __t('privacy.eyebrow') ?></span>
        <h1 class="hero__title"><?= hero_title('privacy.title', '#policy') ?></h1>
        <p class="hero__subtitle"><?= __t('privacy.subtitle') ?></p>
      </div>
    </div>
  </section>

  <!-- Content -->
  <section class="section" id="policy">
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
/* Hero — clear of the nav, with a faint gold shield on the right */
.privacy-hero {
  min-height: max(440px, 56svh);
  align-items: center;
}
.privacy-hero .hero__content {
  padding-block: 8rem 4rem;
}
.privacy-hero .hero__subtitle {
  margin-bottom: 0;
}
.privacy-hero__shield {
  position: absolute;
  z-index: 0;
  top: 50%;
  right: max(4vw, calc((100vw - 1280px) / 2));
  width: clamp(220px, 28vw, 380px);
  height: auto;
  translate: 0 -42%;
  color: var(--col-accent);
  opacity: .22;
  pointer-events: none;
}
@media (max-width: 767px) {
  .privacy-hero {
    min-height: 380px;
  }
  .privacy-hero .hero__content {
    padding-block: 7rem 3rem;
  }
  .privacy-hero__shield {
    right: -40px;
    width: 220px;
    opacity: .12;
  }
}
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
