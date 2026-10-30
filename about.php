<?php
require_once __DIR__ . '/partials/_lang.php';
$page_title       = __r('meta.about.title');
$page_description = __r('meta.about.desc');
$page_css         = ['pages/about.css'];
$page_js          = ['about.js'];
$body_class       = 'page-about';
$current_page     = 'about';
$hero_img         = null;
include 'partials/_head.php';
?>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <!-- SECTION 1 — Hero + Stats -->
  <section class="hero hero--full" aria-label="<?= __t('a11y.page_intro') ?>">
    <div class="hero__bg hero__bg--pattern" aria-hidden="true"></div>

    <div class="hero__content">
      <div class="container">
        <span class="eyebrow eyebrow--light reveal" data-delay="1"><?= __t('about.hero.eyebrow') ?></span>
        <h1 class="hero__title reveal" data-delay="2"><?= hero_title('about.hero.title', '#certifications') ?></h1>
        <p class="hero__subtitle reveal" data-delay="3"><?= __t('about.hero.subtitle') ?></p>
      </div>

      <div class="hero__stats-wrap" aria-label="<?= __t('a11y.key_figures') ?>">
        <div class="container">
          <div class="stats-bar stats-bar--hero" role="list">
            <div class="stats-bar__item" role="listitem">
              <span class="stats-bar__value" data-count="<?= date('Y') - 1984 ?>" data-suffix="+">0</span>
              <span class="stats-bar__label"><?= __t('about.stats.heritage') ?></span>
            </div>
            <div class="stats-bar__item" role="listitem">
              <span class="stats-bar__value" data-count="3" data-suffix="">0</span>
              <span class="stats-bar__label"><?= __t('about.stats.countries') ?></span>
            </div>
            <div class="stats-bar__item" role="listitem">
              <span class="stats-bar__value" data-count="100" data-suffix="%">0</span>
              <span class="stats-bar__label"><?= __t('about.stats.natural') ?></span>
            </div>
            <div class="stats-bar__item" role="listitem">
              <span class="stats-bar__value" data-count="28" data-suffix="–30%">0</span>
              <span class="stats-bar__label"><?= __t('about.stats.concentration') ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- SECTION 2 — Product Promise -->
  <section class="section" aria-labelledby="promise-heading">
    <div class="container">
      <div class="story-grid story-grid--reverse">

        <div class="story-text">
          <h2 id="promise-heading" class="eyebrow eyebrow--accent reveal"><?= __t('about.promise.eyebrow') ?></h2>
          <p class="reveal" data-delay="2"><?= __t('about.promise.p1') ?></p>
          <p class="reveal" data-delay="3"><?= __t('about.promise.p2') ?></p>
          <p class="reveal" data-delay="4"><?= __t('about.promise.p3') ?></p>
          <div class="cluster reveal" data-delay="5" aria-label="<?= __t('a11y.regions') ?>">
            <span class="badge badge--outline"><?= __t('about.promise.region_rw') ?></span>
            <span class="badge badge--outline"><?= __t('about.promise.region_bi') ?></span>
            <span class="badge badge--outline"><?= __t('about.promise.region_cd') ?></span>
          </div>
          <div class="about-cta-row reveal" data-delay="5">
            <a href="/contact" class="btn--text"><?= __t('about.promise.btn_partner') ?></a>
            <a href="#team" class="btn--text btn--text-accent"><?= __r('about.promise.btn_team') ?></a>
          </div>
        </div>

        <div class="story-img-wrap">
          <div class="story-double-img">
            <img src="assets/img/slider/Mobile/hero_tomato.webp" alt="<?= __t('img.great_lakes') ?>" class="story-double-img__primary" loading="lazy">
            <img src="assets/img/slider/Mobile/collection-flatlay.webp" alt="<?= __t('img.products') ?>" class="story-double-img__secondary" loading="lazy">
          </div>
        </div>

      </div>
    </div>
  </section>


  <?php include 'partials/certifications-marquee.php'; ?>


  <!-- SECTION 3 — Brands -->
  <section class="section principles-section on-dark" aria-labelledby="journey-heading">
    <div class="container">
      <header class="section-header reveal">
        <h2 id="journey-heading" class="eyebrow eyebrow--light"><?= __t('about.journey.eyebrow') ?></h2>
      </header>
      <div class="principles-grid">
        <?php foreach (['m1','m2','m3','m4'] as $i => $m): ?>
        <article class="principle-card reveal" data-delay="<?= $i+1 ?>">
          <span class="principle-card__num" aria-hidden="true"><?= __t('about.journey.'.$m.'.year') ?></span>
          <h3 class="principle-card__title"><?= __t('about.journey.'.$m.'.title') ?></h3>
          <p class="principle-card__desc"><?= __t('about.journey.'.$m.'.desc') ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>


  <!-- SECTION 4 — Team -->
  <section class="section team-section" id="team" aria-labelledby="team-heading">
    <div class="container">
      <header class="section-header reveal">
        <span class="eyebrow"><?= __t('about.team.eyebrow') ?></span>
        <h2 id="team-heading"><?= __r('about.team.heading') ?></h2>
      </header>
      <div class="team-grid">
        <?php foreach (['dillux','kumar'] as $i => $m): ?>
        <article class="team-card reveal" data-delay="<?= $i+1 ?>">
          <span class="team-card__role"><?= __t('about.team.'.$m.'.role') ?></span>
          <h3 class="team-card__name"><?= __r('about.team.'.$m.'.name') ?></h3>
          <p class="team-card__bio"><?= __t('about.team.'.$m.'.bio') ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>

</body>
</html>
