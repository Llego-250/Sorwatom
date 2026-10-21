<!-- Skip link — visible on keyboard focus only -->
<a href="#main-content" class="skip-link"><?= __t('a11y.skip_main') ?></a>

<header class="site-nav" id="site-nav" role="banner">
  <div class="container">
    <div class="site-nav__inner">

      <!-- Logo -->
      <a href="/" class="nav-logo" aria-label="<?= __t('a11y.home_link') ?>">
        <img src="/assets/img/logo.png" alt="Sorwatom" class="nav-logo__img" width="140" height="48" loading="eager" decoding="async">
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
</div>

<style>
.nav-logo { display:flex; align-items:center; text-decoration:none; flex-shrink:0; }
.nav-logo__img { height:44px; width:auto; object-fit:contain; display:block; }
.mobile-nav { position:fixed; inset:0; background:var(--col-ground,#0d1e12); z-index:9999; display:flex; flex-direction:column; justify-content:center; align-items:center; opacity:0; visibility:hidden; pointer-events:none; transition:opacity .28s ease,visibility 0s linear .28s; }
.mobile-nav.is-open { opacity:1; visibility:visible; pointer-events:auto; transition:opacity .28s ease,visibility 0s linear 0s; }
.mobile-nav__close { position:absolute; top:18px; right:18px; width:44px; height:44px; background:none; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0; touch-action:manipulation; }
.mobile-nav__close span { position:absolute; width:22px; height:2px; background:rgba(255,255,255,.85); border-radius:2px; }
.mobile-nav__close span:first-child { transform:rotate(45deg); }
.mobile-nav__close span:last-child  { transform:rotate(-45deg); }
.mobile-nav ul { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; align-items:center; gap:var(--space-lg,2rem); }
.mobile-nav a { font-family:var(--font-display,serif); font-size:clamp(1.75rem,8vw,2.5rem); color:white; text-decoration:none; opacity:.85; transition:opacity .15s,color .15s; letter-spacing:-.01em; }
.mobile-nav a:hover, .mobile-nav a[aria-current="page"] { opacity:1; color:var(--col-accent,#c8923a); }


@media (prefers-reduced-motion:reduce) { .mobile-nav { transition:none; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var header   = document.getElementById('site-nav');
  var toggle   = document.getElementById('nav-toggle');
  var overlay  = document.getElementById('mobile-nav');
  var closeBtn = document.getElementById('mobile-nav-close');
  if (!toggle || !overlay) return;
  if (header) {
    function onScroll() { header.classList.toggle('scrolled', window.scrollY > 60); }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
  function openMenu()  { overlay.classList.add('is-open'); overlay.setAttribute('aria-hidden','false'); toggle.setAttribute('aria-expanded','true');  document.body.style.overflow='hidden'; }
  function closeMenu() { toggle.focus(); overlay.classList.remove('is-open'); overlay.setAttribute('aria-hidden','true'); toggle.setAttribute('aria-expanded','false'); document.body.style.overflow=''; }
  toggle.addEventListener('click', function () { overlay.classList.contains('is-open') ? closeMenu() : openMenu(); });
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  overlay.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeMenu); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
});
</script>
