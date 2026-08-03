<?php
http_response_code(404);
$page_title       = '404 — Page Not Found | Sorwatomo';
$page_description = 'The page you are looking for does not exist or has been moved.';
$page_css         = [];
$body_class       = 'page-404';
$current_page     = '';
include 'partials/_head.php';
?>

<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <section class="section-404" aria-label="Page not found">
    <div class="container">
      <div class="404-grid">

        <!-- 404 Mascot Image -->
        <div class="404-img-wrap reveal" data-delay="1">
          <div class="404-img-glow" aria-hidden="true"></div>
          <img
            src="/assets/img/404.png"
            alt="Sorwatom 404 — Page not found"
            class="404-img"
            width="560"
            height="380"
            loading="eager"
            decoding="async"
          >
        </div>

        <!-- 404 Text & Actions -->
        <div class="404-content reveal" data-delay="2">
          <span class="eyebrow eyebrow--light"><?= __t('404.eyebrow') ?></span>
          <h1 class="404-title"><?= __r('404.title') ?></h1>
          <p class="404-subtitle"><?= __t('404.subtitle') ?></p>
          <div class="404-actions">
            <a href="/" class="btn btn--primary"><?= __t('404.btn_home') ?></a>
            <a href="/products" class="btn btn--ghost"><?= __t('404.btn_catalog') ?></a>
          </div>
        </div>

      </div><!-- /.404-grid -->
    </div><!-- /.container -->
  </section>

</main>

<style>
.page-404 {
  background: var(--col-ground, #0d1e12);
  color: #ffffff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.section-404 {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: clamp(5rem, 12vh, 8rem) 0 clamp(4rem, 8vh, 6rem);
  position: relative;
  overflow: hidden;
}

.section-404::before {
  content: '';
  position: absolute;
  top: -20%;
  left: 50%;
  transform: translateX(-50%);
  width: 1000px;
  height: 600px;
  background: radial-gradient(ellipse at center, rgba(200, 146, 58, 0.12) 0%, rgba(13, 30, 18, 0) 70%);
  pointer-events: none;
}

.404-grid {
  display: grid;
  grid-template-columns: 1fr 1.1fr;
  align-items: center;
  gap: clamp(2rem, 5vw, 4rem);
  max-width: 1080px;
  margin: 0 auto;
}

.404-img-wrap {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}

.404-img-glow {
  position: absolute;
  inset: -10%;
  background: radial-gradient(circle, rgba(200, 146, 58, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
  border-radius: 50%;
  filter: blur(20px);
  pointer-events: none;
}

.404-img {
  width: 100%;
  max-width: 460px;
  height: auto;
  object-fit: contain;
  filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.5));
  animation: float404 6s ease-in-out infinite;
}

@keyframes float404 {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.404-content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.404-title {
  font-family: var(--font-display, serif);
  font-size: clamp(2.2rem, 5vw, 3.4rem);
  font-weight: 600;
  line-height: 1.15;
  margin: 0.75rem 0 1rem;
  color: #ffffff;
}

.404-title em {
  font-style: italic;
  color: var(--col-accent, #c8923a);
}

.404-subtitle {
  font-size: var(--step-0, 1.1rem);
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.6;
  max-width: 480px;
  margin-bottom: 2rem;
}

.404-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

@media (max-width: 868px) {
  .404-grid {
    grid-template-columns: 1fr;
    text-align: center;
  }
  .404-content {
    align-items: center;
  }
  .404-subtitle {
    margin-left: auto;
    margin-right: auto;
  }
  .404-actions {
    justify-content: center;
  }
  .404-img {
    max-width: 320px;
  }
}
</style>

<!-- <?php include 'partials/footer.php'; ?> -->

<?php include 'partials/_scripts.php'; ?>

</body>
</html>
