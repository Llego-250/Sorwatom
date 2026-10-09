<!-- Skip link — visible on keyboard focus only -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<?php
$top_langs = [
  'en' => ['name' => 'English', 'flag' => '🇬🇧'],
  'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
  'rw' => ['name' => 'Kinyarwanda', 'flag' => '🇷🇼'],
  'sw' => ['name' => 'Kiswahili', 'flag' => '🇹🇿']
];
?>

<header class="site-nav" id="site-nav" role="banner">
  <div class="container">
    <div class="site-nav__inner">

      <!-- Logo -->
      <a href="/" class="nav-logo" aria-label="Sorwatom — Home">
        <img src="/assets/img/logo.png" alt="Sorwatom" class="nav-logo__img" width="140" height="48" loading="eager" decoding="async">
      </a>

      <!-- Desktop navigation -->
      <nav aria-label="Main navigation">
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

          <!-- Language Icon Selector Dropdown -->
          <li class="nav-lang-item-wrap">
            <div class="nav-lang-dropdown">
              <button
                type="button"
                class="nav-lang-btn"
                id="nav-lang-btn"
                aria-expanded="false"
                aria-haspopup="true"
                aria-label="<?= __t('footer.lang_label') ?>"
              >
                <svg class="nav-lang-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="2" y1="12" x2="22" y2="12"></line>
                  <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
                <span class="nav-lang-code"><?= strtoupper($LANG_CODE) ?></span>
                <svg class="nav-lang-arrow" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>
              <div class="nav-lang-menu" id="nav-lang-menu" role="menu" aria-labelledby="nav-lang-btn">
                <?php foreach ($top_langs as $code => $info): ?>
                <a href="/lang-switch.php?lang=<?= $code ?>" class="nav-lang-menu__item<?= $LANG_CODE === $code ? ' is-active' : '' ?>" role="menuitem">
                  <span class="flag"><?= $info['flag'] ?></span>
                  <span class="label"><?= $info['name'] ?></span>
                  <span class="code">(<?= strtoupper($code) ?>)</span>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
          </li>
        </ul>
      </nav>

      <!-- Mobile hamburger toggle -->
      <button
        class="nav-toggle"
        id="nav-toggle"
        aria-label="<?= __t('nav.open_menu') ?>"
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

<div class="mobile-nav" id="mobile-nav" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Main navigation">
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

  <!-- Mobile Language Switcher Section -->
  <div class="mobile-nav-lang">
    <div class="mobile-nav-lang__head">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="2" y1="12" x2="22" y2="12"></line>
        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
      </svg>
      <span><?= __t('footer.lang_label') ?></span>
    </div>
    <div class="mobile-nav-lang__grid">
      <?php foreach ($top_langs as $code => $info): ?>
      <a href="/lang-switch.php?lang=<?= $code ?>" class="mobile-lang-btn<?= $LANG_CODE === $code ? ' is-active' : '' ?>">
        <span><?= $info['flag'] ?></span>
        <span><?= strtoupper($code) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
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

/* Top Nav Language Selector Dropdown */
.nav-lang-item-wrap { position: relative; margin-left: 6px; }
.nav-lang-dropdown { position: relative; }
.nav-lang-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 50px;
  color: #ffffff;
  font-family: inherit;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}
.nav-lang-btn:hover, .nav-lang-dropdown.is-open .nav-lang-btn {
  background: rgba(200, 146, 58, 0.22);
  border-color: var(--col-accent, #c8923a);
  color: #ffffff;
}
.nav-lang-icon { color: var(--col-accent, #c8923a); }
.nav-lang-arrow { transition: transform 0.2s ease; opacity: 0.75; }
.nav-lang-dropdown.is-open .nav-lang-arrow { transform: rotate(180deg); }

.nav-lang-menu {
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  min-width: 175px;
  background: #112416;
  border: 1px solid rgba(200, 146, 58, 0.35);
  border-radius: 12px;
  padding: 6px;
  box-shadow: 0 16px 36px rgba(0, 0, 0, 0.55);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-8px);
  transition: opacity 0.2s ease, transform 0.2s ease, visibility 0s linear 0.2s;
  z-index: 1000;
}
.nav-lang-dropdown.is-open .nav-lang-menu,
.nav-lang-dropdown:hover .nav-lang-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
  transition: opacity 0.2s ease, transform 0.2s ease, visibility 0s linear 0s;
}

.nav-lang-menu__item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 12px;
  color: rgba(255, 255, 255, 0.85);
  text-decoration: none;
  font-size: 0.84rem;
  border-radius: 8px;
  transition: background 0.15s ease, color 0.15s ease;
}
.nav-lang-menu__item:hover {
  background: rgba(200, 146, 58, 0.18);
  color: #ffffff;
}
.nav-lang-menu__item.is-active {
  background: rgba(200, 146, 58, 0.25);
  color: var(--col-accent, #c8923a);
  font-weight: 600;
}
.nav-lang-menu__item .code { font-size: 0.72rem; opacity: 0.6; margin-left: auto; }

/* Mobile Nav Language Section */
.mobile-nav-lang {
  margin-top: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}
.mobile-nav-lang__head {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--col-accent, #c8923a);
}
.mobile-nav-lang__grid {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  justify-content: center;
}
.mobile-lang-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 8px;
  color: white;
  text-decoration: none;
  font-size: 0.84rem;
  font-weight: 600;
  transition: all 0.2s ease;
}
.mobile-lang-btn:hover, .mobile-lang-btn.is-active {
  background: rgba(200, 146, 58, 0.25);
  border-color: var(--col-accent, #c8923a);
  color: var(--col-accent, #c8923a);
}

@media (prefers-reduced-motion:reduce) { .mobile-nav, .nav-lang-menu { transition:none; } }
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

  // Top Nav Language Dropdown Toggle
  var langBtn = document.getElementById('nav-lang-btn');
  var langDropdown = langBtn ? langBtn.closest('.nav-lang-dropdown') : null;
  if (langBtn && langDropdown) {
    langBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var isOpen = langDropdown.classList.contains('is-open');
      langDropdown.classList.toggle('is-open', !isOpen);
      langBtn.setAttribute('aria-expanded', String(!isOpen));
    });
    document.addEventListener('click', function (e) {
      if (!langDropdown.contains(e.target)) {
        langDropdown.classList.remove('is-open');
        langBtn.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && langDropdown.classList.contains('is-open')) {
        langDropdown.classList.remove('is-open');
        langBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }
});
</script>
