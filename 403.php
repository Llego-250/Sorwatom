<?php
http_response_code(403);
$page_title       = '403 — Access Forbidden | Sorwatom';
$page_description = 'You do not have permission to access this area or resource.';
$page_css         = [];
$body_class       = 'page-403';
$current_page     = '';
include 'partials/_head.php';
?>

<body class="<?= htmlspecialchars($body_class) ?>">

<main id="main-content">
  <section class="section-403" aria-label="Access forbidden">
    <div class="container">
      <div class="err-page-grid">

        <!-- 403 Angry Tomato Mascot Image -->
        <div class="err-page-img-wrap reveal" data-delay="1">
          <div class="err-page-img-glow" aria-hidden="true"></div>
          <img
            src="/assets/img/403.png"
            alt="Sorwatom 403 — Access Forbidden"
            class="err-page-img"
            width="560"
            height="380"
            loading="eager"
            decoding="async"
          >
        </div>

        <!-- 403 Text & Actions -->
        <div class="err-page-content reveal" data-delay="2">
          <span class="eyebrow eyebrow--light"><?= __t('403.eyebrow') ?></span>
          <h1 class="err-page-title"><?= __r('403.title') ?></h1>
          <p class="err-page-subtitle"><?= __t('403.subtitle') ?></p>
          <div class="err-page-actions">
            <a href="/" class="btn btn--primary"><?= __t('403.btn_home') ?></a>
            <a href="/products" class="btn btn--ghost"><?= __t('403.btn_catalog') ?></a>
          </div>
        </div>

      </div><!-- /.err-page-grid -->
    </div><!-- /.container -->
  </section>
</main>

<style>
html, body {
  height: 100%;
}

.page-403 {
  background: var(--col-ground, #0d1e12);
  color: #ffffff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin: 0;
}

#main-content {
  flex: 1;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.section-403 {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem 1.5rem;
  position: relative;
  overflow: hidden;
}

.section-403::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 900px;
  height: 600px;
  background: radial-gradient(ellipse at center, rgba(200, 146, 58, 0.15) 0%, rgba(13, 30, 18, 0) 70%);
  pointer-events: none;
}

.err-page-grid {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  max-width: 680px;
  margin: 0 auto;
  gap: 1.5rem;
}

.err-page-img-wrap {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.err-page-img-glow {
  position: absolute;
  inset: -10%;
  background: radial-gradient(circle, rgba(200, 146, 58, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
  border-radius: 50%;
  filter: blur(25px);
  pointer-events: none;
}

.err-page-img {
  width: 100%;
  max-width: 420px;
  height: auto;
  object-fit: contain;
  filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.5));
  animation: float403 6s ease-in-out infinite;
}

@keyframes float403 {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.err-page-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.err-page-title {
  font-family: var(--font-display, serif);
  font-size: clamp(2.2rem, 5vw, 3.4rem);
  font-weight: 600;
  line-height: 1.15;
  margin: 0.75rem 0 1rem;
  color: #ffffff;
}

.err-page-title em {
  font-style: italic;
  color: var(--col-accent, #c8923a);
}

.err-page-subtitle {
  font-size: var(--step-0, 1.1rem);
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.6;
  max-width: 480px;
  margin: 0 auto 2rem;
}

.err-page-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
}
</style>

<?php include 'partials/_scripts.php'; ?>

</body>
</html>
