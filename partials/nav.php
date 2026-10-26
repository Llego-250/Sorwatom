<!-- Skip link — visible on keyboard focus only -->
<a href="#main-content" class="skip-link"><?= __t('a11y.skip_main') ?></a>

<?php
// Each language is named in its own language
$nav_langs = ['en' => 'English', 'rw' => 'Ikinyarwanda'];
// The same page in that language: the current address with ?lang= set (see _lang.php)
$nav_lang_url = static function (string $code): string {
  $uri = $_SERVER['REQUEST_URI'] ?? '/';
  parse_str((string) parse_url($uri, PHP_URL_QUERY), $query);
  $query['lang'] = $code;
  return strtok($uri, '?') . '?' . http_build_query($query);
};
?>

<header class="site-nav" id="site-nav" role="banner">
  <div class="container">
    <div class="site-nav__inner">

      <!-- Logo -->
      <a href="/" class="nav-logo" aria-label="<?= __t('a11y.home_link') ?>">
        <img src="/assets/img/logo.png" alt="Sorwatom" class="nav-logo__img" width="146" height="60" loading="eager" decoding="async">
      </a>

      <!-- Desktop navigation -->
      <nav aria-label="<?= __t('a11y.main_nav') ?>">
        <ul class="nav-links" id="nav-links" role="list">
          <li>
            <a href="/"<?= ($current_page ?? '') === 'home' ? ' aria-current="page"' : '' ?>><?= __t('nav.home') ?></a>
          </li>
          <li>
            <a href="/products"<?= ($current_page ?? '') === 'products' ? ' aria-current="page"' : '' ?>><?= __t('nav.collection') ?></a>
          </li>
          <li>
            <a href="/about"<?= ($current_page ?? '') === 'about' ? ' aria-current="page"' : '' ?>><?= __t('nav.story') ?></a>
          </li>
          <li>
            <a href="/blog"<?= ($current_page ?? '') === 'blog' ? ' aria-current="page"' : '' ?>><?= __t('nav.journal') ?></a>
          </li>
          <li>
            <a href="/contact" class="nav-cta"<?= ($current_page ?? '') === 'contact' ? ' aria-current="page"' : '' ?>><?= __t('nav.contact') ?></a>
          </li>
          <li>
            <div class="nav-lang" role="group" aria-label="<?= __t('nav.language') ?>">
              <?php foreach ($nav_langs as $code => $name): ?>
              <a href="<?= htmlspecialchars($nav_lang_url($code)) ?>" class="nav-lang__opt" lang="<?= $code ?>" hreflang="<?= $code ?>" title="<?= $name ?>"<?= $LANG_CODE === $code ? ' aria-current="true"' : '' ?>><?= strtoupper($code) ?><span class="nav-lang__name"> <?= $name ?></span></a>
              <?php endforeach; ?>
            </div>
          </li>
        </ul>
      </nav>

      <!-- Mobile hamburger toggle -->
      <button
        class="nav-toggle"
        id="nav-toggle"
        aria-label="<?= __t('nav.open_menu') ?>"
        data-label-open="<?= __t('nav.open_menu') ?>"
        data-label-close="<?= __t('nav.close_menu') ?>"
        aria-expanded="false"
        aria-controls="mobile-nav"
        type="button"
      >
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
      </button>

    </div>
  </div>
</header>

<div class="mobile-nav" id="mobile-nav" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?= __t('a11y.main_nav') ?>">
  <button class="mobile-nav__close" id="mobile-nav-close" aria-label="<?= __t('nav.close_menu') ?>" type="button">
    <span></span>
    <span></span>
  </button>
  <ul role="list">
    <li>
      <a href="/"<?= ($current_page ?? '') === 'home' ? ' aria-current="page"' : '' ?>><?= __t('nav.home') ?></a>
    </li>
    <li>
      <a href="/products"<?= ($current_page ?? '') === 'products' ? ' aria-current="page"' : '' ?>><?= __t('nav.collection') ?></a>
    </li>
    <li>
      <a href="/about"<?= ($current_page ?? '') === 'about' ? ' aria-current="page"' : '' ?>><?= __t('nav.story') ?></a>
    </li>
    <li>
      <a href="/blog"<?= ($current_page ?? '') === 'blog' ? ' aria-current="page"' : '' ?>><?= __t('nav.journal') ?></a>
    </li>
    <li>
      <a href="/contact"<?= ($current_page ?? '') === 'contact' ? ' aria-current="page"' : '' ?>><?= __t('nav.contact') ?></a>
    </li>
  </ul>
  <div class="nav-lang nav-lang--mobile" role="group" aria-label="<?= __t('nav.language') ?>">
    <?php foreach ($nav_langs as $code => $name): ?>
    <a href="<?= htmlspecialchars($nav_lang_url($code)) ?>" class="nav-lang__opt" lang="<?= $code ?>" hreflang="<?= $code ?>"<?= $LANG_CODE === $code ? ' aria-current="true"' : '' ?>><?= $name ?></a>
    <?php endforeach; ?>
  </div>
</div>

<style>
.mobile-nav { position:fixed; inset:0; background:var(--col-ground,#0d1e12); z-index:9999; display:flex; flex-direction:column; justify-content:center; align-items:center; opacity:0; visibility:hidden; pointer-events:none; transition:opacity .28s ease,visibility 0s linear .28s; }
.mobile-nav.is-open { opacity:1; visibility:visible; pointer-events:auto; transition:opacity .28s ease,visibility 0s linear 0s; }
.mobile-nav__close { position:absolute; top:18px; right:18px; width:44px; height:44px; background:none; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0; touch-action:manipulation; }
.mobile-nav__close span { position:absolute; width:22px; height:2px; background:rgba(255,255,255,.85); border-radius:2px; }
.mobile-nav__close span:first-child { transform:rotate(45deg); }
.mobile-nav__close span:last-child  { transform:rotate(-45deg); }
.mobile-nav ul { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; align-items:center; gap:var(--space-lg,2rem); }
.mobile-nav a { font-family:var(--font-display,serif); font-size:clamp(1.75rem,8vw,2.5rem); color:white; text-decoration:none; opacity:.85; transition:opacity .15s,color .15s; letter-spacing:-.01em; }
.mobile-nav a:hover, .mobile-nav a[aria-current="page"] { opacity:1; color:var(--col-accent,#c8923a); }

/* Language switch — EN | RW pill on desktop, full names in the mobile menu */
.nav-lang { display:inline-flex; align-items:center; gap:2px; padding:3px; border:1.5px solid rgba(255,255,255,.35); border-radius:var(--radius-pill,9999px); }
.nav-lang .nav-lang__opt { display:inline-flex; align-items:center; justify-content:center; min-width:2.6em; padding:6px 10px; border-radius:var(--radius-pill,9999px); font-family:var(--font-body,sans-serif); font-size:.78rem; font-weight:600; letter-spacing:.06em; line-height:1; color:#fff; text-decoration:none; opacity:.7; transition:background .2s,opacity .2s; }
.nav-lang .nav-lang__opt:hover { opacity:1; color:#fff; }
.nav-lang .nav-lang__opt[aria-current="true"] { background:var(--col-accent,#c8923a); opacity:1; }
.nav-lang__name { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; }
.nav-lang--mobile { margin-top:var(--space-xl,4rem); }
.nav-lang--mobile .nav-lang__opt { font-size:1rem; letter-spacing:.02em; padding:10px 18px; }

@media (prefers-reduced-motion:reduce) { .mobile-nav { transition:none; } }
</style>

