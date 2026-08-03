<?php
$page_title       = 'Get in Touch — Sorwatom';
$page_description = 'Contact Sorwatom for distribution, wholesale export, or general inquiries. Offices in Rwanda, Burundi and the DRC.';
$page_css         = ['pages/contact.css'];
$page_js          = ['contact.js'];
$body_class       = 'page-contact';
$current_page     = 'contact';
$hero_img         = 'contact-map';
$hero_img_mobile  = 'contact-map.webp';
include 'partials/_head.php';
?>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <!-- SECTION 1 — HERO -->
  <section class="hero hero--full" aria-label="Contact page introduction">
    <picture>
      <source media="(max-width: 767px)" srcset="/assets/img/slider/Mobile/contact-map.webp" type="image/webp">
      <img class="hero__bg" src="/assets/img/slider/contact-map.webp" alt="" aria-hidden="true" fetchpriority="high" loading="eager" decoding="async">
    </picture>
    <div class="hero__content">
      <div class="container">
        <p class="eyebrow eyebrow--light reveal" data-delay="1"><?= __t('contact.hero.eyebrow') ?></p>
        <h1 class="hero__title reveal" data-delay="2"><?= __r('contact.hero.title') ?></h1>
        <p class="hero__subtitle reveal" data-delay="3"><?= __t('contact.hero.subtitle') ?></p>
        <div class="cluster reveal" data-delay="4" aria-label="Countries we operate in">
          <span class="country-flag">🇷🇼 <span>Rwanda</span></span>
          <span class="country-flag">🇧🇮 <span>Burundi</span></span>
          <span class="country-flag">🇨🇩 <span>DRC</span></span>
        </div>
      </div>
    </div>
  </section>


  <!-- SECTION 2 — FORM + OFFICES -->
  <section class="section" aria-labelledby="contact-form-heading">
    <div class="container">
      <div class="contact-grid">

        <div class="form-card reveal">
          <p class="eyebrow eyebrow--accent"><?= __t('contact.form.eyebrow') ?></p>
          <h2 id="contact-form-heading"><?= __t('contact.form.heading') ?></h2>
          <form id="contact-form" novalidate>
            <input type="hidden" name="inquiry_type" id="inquiry_type" value="distribution">
            <div class="form-grid">
              <div class="form-field">
                <label for="name"><?= __t('contact.form.name') ?> <span aria-hidden="true">*</span></label>
                <input type="text" id="name" name="name" required autocomplete="name" placeholder="<?= __t('contact.form.placeholder.name') ?>">
              </div>
              <div class="form-field">
                <label for="company"><?= __t('contact.form.company') ?> <span class="label-optional"><?= __t('contact.form.optional') ?></span></label>
                <input type="text" id="company" name="company" autocomplete="organization" placeholder="<?= __t('contact.form.placeholder.company') ?>">
              </div>
              <div class="form-field">
                <label for="email"><?= __t('contact.form.email') ?> <span aria-hidden="true">*</span></label>
                <input type="email" id="email" name="email" required autocomplete="email" placeholder="<?= __t('contact.form.placeholder.email') ?>">
              </div>
              <div class="form-field">
                <label for="mobile"><?= __t('contact.form.mobile') ?></label>
                <input type="tel" id="mobile" name="mobile" autocomplete="tel" placeholder="<?= __t('contact.form.placeholder.mobile') ?>">
              </div>
              <div class="form-field form-field--full">
                <label for="message"><?= __t('contact.form.message') ?> <span aria-hidden="true">*</span></label>
                <textarea id="message" name="message" required rows="5" placeholder="<?= __t('contact.form.placeholder.message') ?>"></textarea>
              </div>
            </div>
            <div class="form-feedback" id="form-feedback" role="alert" aria-live="polite"></div>
            <button type="submit" class="btn btn--primary" id="form-submit">
              <span class="btn-label"><?= __t('contact.form.submit') ?></span>
              <span class="btn-arrow" aria-hidden="true">→</span>
            </button>
          </form>
        </div>

        <div class="offices-col reveal" data-delay="1">
          <p class="eyebrow"><?= __t('contact.offices.eyebrow') ?></p>
          <article class="office-card office-card--hq" aria-label="Rwanda headquarters">
            <div class="office-card__country"><span class="office-card__flag" aria-hidden="true">🇷🇼</span><?= __t('contact.offices.rw') ?></div>
            <p class="office-card__number">+250 787 160 000</p>
            <div class="office-card__detail">
              <a href="mailto:info@sorwatom.com">info@sorwatom.com</a><br>
              Kigali, Ndera - Mulindi<br>
              <span><?= __t('contact.offices.hours') ?></span>
            </div>
          </article>
          <article class="office-card" aria-label="Burundi & DRC presence">
            <div class="office-card__country"><span class="office-card__flag" aria-hidden="true">🇧🇮 🇨🇩</span> Regional Operations</div>
            <div class="office-card__detail">
              Burundi &amp; Eastern DRC<br>
              <span>Contact HQ for local distribution</span>
            </div>
          </article>
        </div>

      </div>
    </div>
  </section>


  <!-- SECTION 3 — FAQ -->
  <section class="section section--faq on-dark" aria-labelledby="faq-heading">
    <div class="container">
      <div class="faq-grid">
        <div class="faq-prompt reveal">
          <p class="eyebrow eyebrow--light"><?= __t('contact.faq.eyebrow') ?></p>
          <h2 id="faq-heading"><?= __r('contact.faq.heading') ?></h2>
        </div>
        <div class="accordion reveal" data-delay="1" role="list">
          <?php foreach (['q1','q2','q3','q4'] as $i => $q): $n = $i+1; ?>
          <div class="accordion__item" role="listitem">
            <button class="accordion__trigger" type="button" aria-expanded="false" aria-controls="faq-body-<?= $n ?>" id="faq-trigger-<?= $n ?>">
              <?= __t('contact.faq.'.$q.'.q') ?>
              <span class="accordion__icon" aria-hidden="true">+</span>
            </button>
            <div class="accordion__body" id="faq-body-<?= $n ?>" role="region" aria-labelledby="faq-trigger-<?= $n ?>">
              <div class="accordion__content"><?= __t('contact.faq.'.$q.'.a') ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>

</body>
</html>
