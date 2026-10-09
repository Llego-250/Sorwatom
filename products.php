<?php
$page_title       = 'The Collection — Sorwatom';
$page_description = 'Six essential East African products: tomato paste, ketchup, vinegar and pilau masala. 100% natural, internationally certified.';
$page_css         = ['pages/products.css'];
$body_class       = 'page-products';
$current_page     = 'products';
$page_js          = [];
$hero_img         = 'collection-flatlay';
$hero_img_mobile  = 'collection-flatlay.webp';
include 'partials/_head.php';
?>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <!-- SECTION 1 — HERO -->
  <section class="hero hero--full" aria-label="Page introduction">
    <picture>
      <source media="(max-width: 767px)" srcset="/assets/img/slider/Mobile/collection-flatlay.webp" type="image/webp">
      <img class="hero__bg" src="/assets/img/slider/collection-flatlay.webp" alt="" aria-hidden="true" fetchpriority="high" loading="eager" decoding="async">
    </picture>
    <div class="hero__content">
      <div class="container">
        <p class="eyebrow eyebrow--light"><?= __r('products.hero.eyebrow') ?></p>
        <h1 class="hero__title"><?= __r('products.hero.title') ?></h1>
      </div>
    </div>
  </section>


  <!-- SECTION 2 — FILTER + PRODUCT GRID -->
  <section class="section" id="collection" aria-labelledby="collection-heading">
    <div class="container">

      <div class="products-filter-row">
        <div class="filter-pills" role="group" aria-label="Filter products by category">
          <button class="filter-pill active" data-filter="all"        aria-pressed="true"><?= __t('products.filter.all') ?></button>
          <button class="filter-pill"        data-filter="paste"      aria-pressed="false"><?= __t('products.filter.paste') ?></button>
          <button class="filter-pill"        data-filter="condiments" aria-pressed="false"><?= __t('products.filter.condiments') ?></button>
          <button class="filter-pill"        data-filter="spices"     aria-pressed="false"><?= __t('products.filter.spices') ?></button>
        </div>
      </div>

      <div class="grid-3 products-grid" id="products-grid" aria-label="Product collection" aria-live="polite">

        <article class="product-card reveal" data-category="paste" data-delay="1">
          <a href="/product-tomato-paste" class="product-card__link" aria-label="<?= __t('products.p50.name') ?> — view product details">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/tomatoes_paste_50g.png" alt="Sorwatom Tomato Paste 50g sachet" loading="lazy" width="400" height="400">
              <div class="product-card__badges" aria-hidden="true">
                <span class="badge badge--outline"><?= __t('products.badge.sachet') ?></span>
                <span class="badge badge--dark"><?= __t('products.badge.travel') ?></span>
              </div>
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('products.p50.cat') ?></p>
              <h2 class="product-card__name"><?= __t('products.p50.name') ?></h2>
              <p class="product-card__desc"><?= __t('products.p50.desc') ?></p>
              <p class="product-card__format"><span><?= __t('products.format.sachet_50') ?></span><span class="product-card__format-arrow" aria-hidden="true">&#8594;</span></p>
            </div>
          </a>
        </article>

        <article class="product-card reveal" data-category="paste" data-delay="2">
          <a href="/product-tomato-paste" class="product-card__link" aria-label="<?= __t('products.p70.name') ?> — view product details">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/tomatoes_paste_70g.png" alt="Sorwatom Tomato Paste 70g sachet" loading="lazy" width="400" height="400">
              <div class="product-card__badges" aria-hidden="true">
                <span class="badge badge--outline"><?= __t('products.badge.sachet') ?></span>
                <span class="badge badge--accent"><?= __t('products.badge.bestseller') ?></span>
              </div>
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('products.p70.cat') ?></p>
              <h2 class="product-card__name"><?= __t('products.p70.name') ?></h2>
              <p class="product-card__desc"><?= __t('products.p70.desc') ?></p>
              <p class="product-card__format"><span><?= __t('products.format.sachet_70') ?></span><span class="product-card__format-arrow" aria-hidden="true">&#8594;</span></p>
            </div>
          </a>
        </article>

        <article class="product-card reveal" data-category="paste" data-delay="3">
          <a href="/product-tomato-paste" class="product-card__link" aria-label="<?= __t('products.p800.name') ?> — view product details">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/tomatoes_paste_800g.png" alt="Sorwatom Tomato Paste 800g tin" loading="lazy" width="400" height="400">
              <div class="product-card__badges" aria-hidden="true"><span class="badge badge--outline"><?= __t('products.badge.tin') ?></span></div>
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('products.p800.cat') ?></p>
              <h2 class="product-card__name"><?= __t('products.p800.name') ?></h2>
              <p class="product-card__desc"><?= __t('products.p800.desc') ?></p>
              <p class="product-card__format"><span><?= __t('products.format.tin_800') ?></span><span class="product-card__format-arrow" aria-hidden="true">&#8594;</span></p>
            </div>
          </a>
        </article>

        <article class="product-card reveal" data-category="condiments" data-delay="4">
          <a href="/product-ketchup" class="product-card__link" aria-label="<?= __t('products.ketchup.name') ?> — view product details">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/ketchup.png" alt="Sorwatom Tomato Ketchup glass bottle" loading="lazy" width="400" height="400">
              <div class="product-card__badges" aria-hidden="true"><span class="badge badge--outline"><?= __t('products.badge.glass') ?></span></div>
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('products.ketchup.cat') ?></p>
              <h2 class="product-card__name"><?= __t('products.ketchup.name') ?></h2>
              <p class="product-card__desc"><?= __t('products.ketchup.desc') ?></p>
              <p class="product-card__format"><span><?= __t('products.format.glass') ?></span><span class="product-card__format-arrow" aria-hidden="true">&#8594;</span></p>
            </div>
          </a>
        </article>

        <article class="product-card reveal" data-category="condiments" data-delay="1">
          <a href="/product-vinegar" class="product-card__link" aria-label="<?= __t('products.vinegar.name') ?> — view product details">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/vinegar.png" alt="Sorwatom Pure Vinegar glass bottle" loading="lazy" width="400" height="400">
              <div class="product-card__badges" aria-hidden="true"><span class="badge badge--outline"><?= __t('products.badge.glass') ?></span></div>
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('products.vinegar.cat') ?></p>
              <h2 class="product-card__name"><?= __t('products.vinegar.name') ?></h2>
              <p class="product-card__desc"><?= __t('products.vinegar.desc') ?></p>
              <p class="product-card__format"><span><?= __t('products.format.glass') ?></span><span class="product-card__format-arrow" aria-hidden="true">&#8594;</span></p>
            </div>
          </a>
        </article>

        <article class="product-card reveal" data-category="spices" data-delay="2">
          <a href="/product-masala" class="product-card__link" aria-label="<?= __t('products.masala.name') ?> — view product details">
            <div class="product-card__img-wrap">
              <img src="assets/img/products/masala.png" alt="Sorwatom Pilau Masala spice pouch" loading="lazy" width="400" height="400">
              <div class="product-card__badges" aria-hidden="true"><span class="badge badge--outline"><?= __t('products.badge.pouch') ?></span></div>
            </div>
            <div class="product-card__body">
              <p class="product-card__category"><?= __t('products.masala.cat') ?></p>
              <h2 class="product-card__name"><?= __t('products.masala.name') ?></h2>
              <p class="product-card__desc"><?= __t('products.masala.desc') ?></p>
              <p class="product-card__format"><span><?= __t('products.format.pouch') ?></span><span class="product-card__format-arrow" aria-hidden="true">&#8594;</span></p>
            </div>
          </a>
        </article>

      </div>
    </div>
  </section>


  <!-- SECTION 3 — WHOLESALE CTA -->
  <section class="section--sm products-cta" aria-labelledby="cta-heading">
    <div class="container">
      <div class="cta-block">
        <div class="cta-block__text">
          <span class="cta-block__eyebrow"><?= __t('products.cta.eyebrow') ?></span>
          <h2 class="cta-block__title" id="cta-heading"><?= __r('products.cta.heading') ?></h2>
          <p class="cta-block__body"><?= __t('products.cta.body') ?></p>
        </div>
        <div class="cta-block__actions">
          <a href="/contact" class="btn btn--primary"><?= __r('products.cta.btn') ?></a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>

<script>
(function () {
  var pills = document.querySelectorAll('.filter-pill');
  var cards = document.querySelectorAll('.product-card[data-category]');
  function applyFilter(filter) {
    cards.forEach(function (card) { card.dataset.hidden = (filter === 'all' || card.dataset.category === filter) ? 'false' : 'true'; });
  }
  pills.forEach(function (pill) {
    pill.addEventListener('click', function () {
      pills.forEach(function (p) { p.classList.remove('active'); p.setAttribute('aria-pressed','false'); });
      pill.classList.add('active'); pill.setAttribute('aria-pressed','true');
      applyFilter(pill.dataset.filter);
    });
  });
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) { if (entry.isIntersecting) { entry.target.classList.add('revealed'); observer.unobserve(entry.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function (el) { observer.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('revealed'); });
  }
})();
</script>

</body>
</html>
