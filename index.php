<?php
$page_title       = 'Sorwatom — Musosi Fresh from the Great Lakes';
$page_description = 'Sorwatom produces 100% natural tomato paste, ketchup, masala and vinegar crafted in the heart of East Africa since 1984.';
$page_css         = ['pages/home.css'];
$body_class       = 'page-home';
$current_page     = 'home';
$hero_img         = 'hero-tomato';
$hero_img_mobile  = 'hero_tomato.webp';
include 'partials/_head.php';
?>

<body class="<?= $body_class ?>">

<?php include 'partials/nav.php'; ?>

<main id="main">

  <!-- SECTION 1 — HERO -->
  <section class="hero hero--full" aria-label="Homepage hero">
    <picture>
      <source media="(max-width: 767px)" srcset="/assets/img/slider/Mobile/hero_tomato.webp" type="image/webp">
      <img class="hero__bg" src="/assets/img/slider/hero-tomato.webp" alt="" aria-hidden="true" fetchpriority="high" loading="eager" decoding="async">
    </picture>
    <a href="#main" class="skip-link">Skip to content</a>
    <div class="hero__content">
      <div class="container">
        <p class="hero-eyebrow"><?= __t('home.hero.eyebrow') ?></p>
        <h1 class="hero__title"><?= __r('home.hero.title') ?></h1>
        <p class="hero__subtitle"><?= __t('home.hero.subtitle') ?></p>
        <div class="hero__actions">
          <a href="/products" class="btn btn--ghost"><?= __t('home.hero.explore') ?></a>
          <a href="/about" class="btn--text" style="color:rgba(255,255,255,0.75);"><?= __t('home.hero.story') ?></a>
        </div>
      </div>
    </div>
    <div class="hero__scroll" aria-hidden="true">
      <div class="scroll-indicator">
        <span><?= __t('home.hero.scroll') ?></span>
        <span class="scroll-indicator__line"></span>
      </div>
    </div>
  </section>


  <!-- SECTION 2 — TICKER -->
  <div class="ticker" aria-hidden="true">
    <div class="ticker__track">
      <span class="ticker__item"><?= __r('home.ticker') ?></span>
      <span class="ticker__item"><?= __r('home.ticker') ?></span>
    </div>
  </div>


  <!-- SECTION 3 — WHY SORWATOM -->
  <section class="section" aria-labelledby="why-heading">
    <div class="container">
      <span class="eyebrow"><?= __t('home.why.eyebrow') ?></span>
      <h2 id="why-heading" style="max-width:560px; margin-bottom:var(--space-xl);"><?= __r('home.why.heading') ?></h2>
      <div class="grid-3">
        <article class="pillar reveal" data-delay="1">
          <span class="pillar__num" aria-hidden="true">01</span>
          <h3 class="pillar__title"><?= __t('home.why.p1.title') ?></h3>
          <p class="pillar__desc"><?= __t('home.why.p1.desc') ?></p>
        </article>
        <article class="pillar reveal" data-delay="3">
          <span class="pillar__num" aria-hidden="true">02</span>
          <h3 class="pillar__title"><?= __t('home.why.p2.title') ?></h3>
          <p class="pillar__desc"><?= __t('home.why.p2.desc') ?></p>
        </article>
        <article class="pillar reveal" data-delay="4">
          <span class="pillar__num" aria-hidden="true">03</span>
          <h3 class="pillar__title"><?= __t('home.why.p3.title') ?></h3>
          <p class="pillar__desc"><?= __t('home.why.p3.desc') ?></p>
        </article>
      </div>
    </div>
  </section>


  <!-- SECTION 4 — SIGNATURE OFFERINGS -->
  <section class="section signature-offerings" aria-labelledby="offerings-heading">
    <div class="container">
      <div class="section-header signature-offerings__header">
        <div class="section-header__body">
          <span class="eyebrow"><?= __t('home.offerings.eyebrow') ?></span>
          <h2 id="offerings-heading" class="signature-offerings__title"><?= __r('home.offerings.heading') ?></h2>
        </div>
        <a href="/products" class="section-header__link"><?= __t('home.offerings.view_all') ?></a>
      </div>

      <div class="signature-offerings__grid grid-3">
        <article class="product-card product-card--signature reveal" data-delay="1">
          <a href="/product-tomato-paste" class="product-card__link" aria-label="<?= __t('home.offerings.paste.name') ?> — view product">
            <div class="product-card__img-wrap">
              <div class="product-card__badges"><span class="badge badge--dark">Bestseller</span></div>
              <img src="assets/img/products/tomatoes_paste_70g.png" alt="Sorwatom Tomato Paste 70g sachet" loading="lazy" width="400" height="400">
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('home.offerings.paste.cat') ?></p>
              <h3 class="product-card__name"><?= __t('home.offerings.paste.name') ?></h3>
              <p class="product-card__desc"><?= __t('home.offerings.paste.desc') ?></p>
            </div>
          </a>
        </article>

        <article class="product-card product-card--signature reveal" data-delay="2">
          <a href="/product-ketchup" class="product-card__link" aria-label="<?= __t('home.offerings.ketchup.name') ?> — view product">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/ketchup.png" alt="Sorwatom Tomato Ketchup bottle" loading="lazy" width="400" height="400">
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('home.offerings.ketchup.cat') ?></p>
              <h3 class="product-card__name"><?= __t('home.offerings.ketchup.name') ?></h3>
              <p class="product-card__desc"><?= __t('home.offerings.ketchup.desc') ?></p>
            </div>
          </a>
        </article>

        <article class="product-card product-card--signature reveal" data-delay="3">
          <a href="/product-masala" class="product-card__link" aria-label="<?= __t('home.offerings.masala.name') ?> — view product">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/masala.png" alt="Sorwatom Pilau Masala packet" loading="lazy" width="400" height="400">
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('home.offerings.masala.cat') ?></p>
              <h3 class="product-card__name"><?= __t('home.offerings.masala.name') ?></h3>
              <p class="product-card__desc"><?= __t('home.offerings.masala.desc') ?></p>
            </div>
          </a>
        </article>
      </div>
    </div>
  </section>


  <!-- SECTION 5 — ABOUT US -->
  <?php $heritage_years = date('Y') - 1984; ?>
  <section class="section section--lg" aria-labelledby="story-heading">
    <div class="container">
      <div class="story-grid">
        <div class="story-img-wrap reveal" data-delay="1">
          <img src="assets/img/slider/Mobile/collection-flatlay.png" alt="Great Lakes region, the home of Sorwatom" loading="lazy" width="600" height="750">
          <div class="story-badge">
            <span class="story-badge__value"><?= __t('home.story.badge_v', ['years' => $heritage_years]) ?></span>
            <span class="story-badge__label"><?= __t('home.story.badge_l') ?></span>
          </div>
        </div>
        <div class="story-text reveal" data-delay="2">
          <div class="story-text__intro">
            <span class="eyebrow"><?= __t('home.story.eyebrow') ?></span>
            <h2 id="story-heading" class="story-text__title"><?= __r('home.story.title') ?></h2>
          </div>
          <div class="story-text__block">
            <p><?= __t('home.story.p1') ?></p>
            <p><?= __t('home.story.p2') ?></p>
          </div>
          <a href="/about" class="btn--text"><?= __t('home.story.cta') ?></a>
        </div>
      </div>
    </div>
  </section>


  <!-- SECTION 6 — CONTACT CTA -->
  <section class="section section--cta" aria-labelledby="cta-heading">
    <div class="container">
      <div class="cta-block">
        <div class="cta-block__text">
          <span class="cta-block__eyebrow"><?= __t('home.cta.eyebrow') ?></span>
          <h2 class="cta-block__title" id="cta-heading"><?= __r('home.cta.heading') ?></h2>
          <p class="cta-block__body"><?= __t('home.cta.body') ?></p>
        </div>
        <div class="cta-block__actions">
          <a href="/contact" class="btn btn--primary"><?= __t('home.cta.btn') ?></a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>

</body>
</html>
