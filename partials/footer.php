<footer class="site-footer" role="contentinfo">
  <div class="container">

    <div class="footer-grid">

      <!-- Col 1: Brand -->
      <div class="footer-brand footer-col">
        <a href="/" class="footer-brand__logo-link" aria-label="Sorwatomo — Home">
          <img src="/assets/img/logo.png" alt="Sorwatom" class="footer-brand__logo-img" width="130" height="44" loading="lazy" decoding="async" style="filter:none">
        </a>
        <p><?= __t('footer.tagline') ?></p>
      </div>

      <!-- Col 2: Explore links -->
      <div class="footer-col">
        <h4><?= __t('footer.explore') ?></h4>
        <ul role="list">
          <li><a href="/about"><?= __t('footer.link.story') ?></a></li>
          <li><a href="/products"><?= __t('footer.link.catalog') ?></a></li>
          <li><a href="/blog"><?= __t('footer.link.journal') ?></a></li>
          <li><a href="/contact"><?= __t('footer.link.contact') ?></a></li>
        </ul>
      </div>

      <!-- Col 3: Headquarters -->
      <div class="footer-col footer-hq">
        <h4><?= __t('footer.hq') ?></h4>
        <address class="footer-address">
          <p><?= __r('footer.address') ?></p>
          <p><a href="tel:+250787160000">(+250) 787 160 000</a></p>
          <p><a href="mailto:info@sorwatom.com">info@sorwatom.com</a></p>
        </address>
      </div>

      <!-- Col 4: Newsletter -->
      <div class="footer-col footer-newsletter">
        <h4><?= __t('footer.newsletter.heading') ?></h4>
        <p class="footer-newsletter__desc"><?= __t('footer.newsletter.desc') ?></p>
        <form
          class="footer-newsletter__form"
          id="newsletter-form"
          novalidate
          data-action="/newsletter"
          data-msg-ok="<?= __t('footer.newsletter.success') ?>"
          data-msg-err="<?= __t('footer.newsletter.error') ?>"
          data-msg-invalid="<?= __t('footer.newsletter.error_email') ?>">
          <!-- Honeypot — leave blank -->
          <input type="text" name="hp_website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">
          <div class="footer-newsletter__field">
            <label for="nl-email" class="sr-only"><?= __t('footer.newsletter.placeholder') ?></label>
            <input
              type="email"
              id="nl-email"
              name="email"
              placeholder="<?= __t('footer.newsletter.placeholder') ?>"
              required
              autocomplete="email">
            <button type="submit"><?= __t('footer.newsletter.btn') ?></button>
          </div>
          <p class="footer-newsletter__msg" role="status" aria-live="polite"></p>
        </form>
      </div>

    </div><!-- /.footer-grid -->

    <!-- Bottom bar -->
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <?= __t('footer.rights') ?></p>
      <nav class="footer-bottom__right" aria-label="Social, legal and language">
        <div class="footer-social">
          <a href="https://www.instagram.com/sorwatom_rw/" rel="noopener noreferrer" aria-label="Sorwatomo on Instagram">INSTAGRAM</a>
          <a href="https://www.linkedin.com/in/sorwatom-ltd-6b2a48177/" rel="noopener noreferrer" aria-label="Sorwatomo on LinkedIn">LINKEDIN</a>
          <a href="https://www.facebook.com/SORWATOM/" rel="noopener noreferrer" aria-label="Sorwatomo on Facebook">FACEBOOK</a>
          <a href="https://www.X.com/sorwatom/" rel="noopener noreferrer" aria-label="Sorwatomo on Twitter">TWITTER</a>
          <a href="/privacy"><?= __t('footer.privacy') ?></a>
        </div>

        <!-- Language switcher -->
        <div class="lang-switcher" role="group" aria-label="<?= __t('footer.lang_label') ?>">
          <?php
          global $LANG_CODE, $LANG;
          $langs = ['en' => 'EN', 'fr' => 'FR', 'sw' => 'SW'];
          foreach ($langs as $code => $label):
          ?>
          <a
            href="/lang-switch.php?lang=<?= $code ?>"
            class="lang-switcher__btn<?= $LANG_CODE === $code ? ' lang-switcher__btn--active' : '' ?>"
            aria-label="<?= htmlspecialchars($LANG['lang'][$code] ?? $label) ?>"
            <?= $LANG_CODE === $code ? 'aria-current="true"' : '' ?>
          ><?= $label ?></a>
          <?php endforeach; ?>
        </div>
      </nav>
    </div>

  </div><!-- /.container -->
</footer>

<style>
.footer-brand__logo-link { display:inline-block; text-decoration:none; margin-bottom:var(--space-sm); }
.footer-brand__logo-img  { height:40px; width:auto; object-fit:contain; display:block; }
.footer-address { font-style:normal; display:flex; flex-direction:column; gap:6px; }
.footer-address p { font-size:var(--step-0); color:rgba(255,255,255,.75); line-height:1.6; margin:0; }
.footer-address a { color:rgba(255,255,255,.75); text-decoration:none; transition:color var(--dur-fast); }
.footer-address a:hover { color:var(--col-accent); }

/* Bottom bar layout */
.footer-bottom__right { display:flex; align-items:center; gap:var(--space-md); flex-wrap:wrap; }

/* Language switcher */
.lang-switcher { display:flex; align-items:center; gap:4px; }
.lang-switcher__btn {
  display:inline-block;
  padding:4px 9px;
  font-size:11px;
  font-weight:600;
  letter-spacing:.08em;
  color:rgba(255,255,255,.5);
  text-decoration:none;
  border:1px solid rgba(255,255,255,.15);
  border-radius:4px;
  transition:color .15s, border-color .15s, background .15s;
}
.lang-switcher__btn:hover { color:white; border-color:rgba(255,255,255,.4); }
.lang-switcher__btn--active { color:var(--col-accent); border-color:var(--col-accent); background:rgba(200,146,58,.1); }

/* Screen-reader utility */
.sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
</style>
