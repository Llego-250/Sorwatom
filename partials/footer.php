<footer class="site-footer" role="contentinfo">
  <div class="container">

    <div class="footer-grid">

      <!-- Col 1: Brand -->
      <div class="footer-brand footer-col">
        <a href="/" class="footer-brand__logo-link" aria-label="Sorwatom — Home">
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
          <p><a href="https://wa.me/250787160000" target="_blank" rel="noopener noreferrer">+250 787 160 000</a></p>
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
      <nav class="footer-bottom__right" aria-label="Social and legal">
        <div class="footer-social">
          <a href="https://www.instagram.com/sorwatom_rw/" rel="noopener noreferrer" aria-label="Sorwatom on Instagram">INSTAGRAM</a>
          <a href="https://www.tiktok.com/@sorwatom_rw" rel="noopener noreferrer" aria-label="Sorwatom on Tiktok">TIKTOK</a>
          <a href="https://www.facebook.com/SORWATOM/" rel="noopener noreferrer" aria-label="Sorwatom on Facebook">FACEBOOK</a>
          <a href="https://www.X.com/sorwatom/" rel="noopener noreferrer" aria-label="Sorwatom on Twitter">TWITTER</a>
          <a href="/privacy"><?= __t('footer.privacy') ?></a>
        </div>
      </nav>
    </div>

  </div><!-- /.container -->
</footer>

<?php include __DIR__ . '/popup-card.php'; ?>

<style>
.footer-brand__logo-link { display:inline-block; text-decoration:none; margin-bottom:var(--space-sm); }
.footer-brand__logo-img  { height:40px; width:auto; object-fit:contain; display:block; }
.footer-address { font-style:normal; display:flex; flex-direction:column; gap:6px; }
.footer-address p { font-size:var(--step-0); color:rgba(255,255,255,.75); line-height:1.6; margin:0; }
.footer-address a { color:rgba(255,255,255,.75); text-decoration:none; transition:color var(--dur-fast); }
.footer-address a:hover { color:var(--col-accent); }

/* Bottom bar layout */
.footer-bottom__right { display:flex; align-items:center; gap:var(--space-md); flex-wrap:wrap; }

/* Screen-reader utility */
.sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }

/* Newsletter column */
.footer-newsletter__desc {
  font-size: var(--step--1);
  color: rgba(255,255,255,.65);
  line-height: 1.6;
  margin: 0 0 var(--space-sm);
}
.footer-newsletter__field {
  display: flex;
  gap: 0;
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 6px;
  overflow: hidden;
  transition: border-color .2s;
}
.footer-newsletter__field:focus-within { border-color: var(--col-accent); }
.footer-newsletter__field input[type="email"] {
  flex: 1;
  min-width: 0;
  padding: 9px 12px;
  background: rgba(255,255,255,.07);
  border: none;
  color: #fff;
  font-size: var(--step--1);
  outline: none;
}
.footer-newsletter__field input[type="email"]::placeholder { color: rgba(255,255,255,.35); }
.footer-newsletter__field button {
  padding: 9px 14px;
  background: var(--col-accent);
  color: #fff;
  border: none;
  font-size: var(--step--1);
  font-weight: 600;
  letter-spacing: .04em;
  cursor: pointer;
  white-space: nowrap;
  transition: background .2s;
}
.footer-newsletter__field button:hover { background: color-mix(in srgb, var(--col-accent) 85%, #fff); }
.footer-newsletter__msg {
  margin: 8px 0 0;
  font-size: var(--step--1);
  min-height: 1.4em;
  line-height: 1.4;
}
.footer-newsletter__msg--ok  { color: #6ee7b7; }
.footer-newsletter__msg--err { color: #fca5a5; }
</style>

<script>
(function () {
  const form = document.getElementById('newsletter-form');
  if (!form) return;
  const msg      = form.querySelector('.footer-newsletter__msg');
  const btn      = form.querySelector('button[type="submit"]');
  const msgOk    = form.dataset.msgOk;
  const msgErr   = form.dataset.msgErr;
  const msgBad   = form.dataset.msgInvalid;

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const email = form.querySelector('#nl-email').value.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      show(msgBad, 'err'); return;
    }
    const originalLabel = btn.textContent;
    btn.disabled = true;
    btn.textContent = '…';
    try {
      const fd = new FormData(form);
      const res = await fetch(form.dataset.action, { method: 'POST', body: fd });
      const json = await res.json();
      show(json.ok ? msgOk : (json.message || msgErr), json.ok ? 'ok' : 'err');
      if (json.ok) { form.querySelector('#nl-email').value = ''; btn.style.display = 'none'; }
    } catch (_) {
      show(msgErr, 'err');
    } finally {
      btn.disabled = false;
      if (btn.style.display !== 'none') btn.textContent = originalLabel;
    }
  });

  function show(text, type) {
    msg.textContent = text;
    msg.className = 'footer-newsletter__msg footer-newsletter__msg--' + type;
  }
})();
</script>
