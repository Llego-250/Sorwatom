'use strict';

/* Behaviour for the partials on every page — nav, newsletter popup and footer.
   Loaded before main.js so these listeners run first, as they did when inline. */


/* ----------------------------------------------------------------
   1. NAV — scroll state and mobile menu (partials/nav.php)
---------------------------------------------------------------- */
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


/* ----------------------------------------------------------------
   2. NEWSLETTER POPUP (partials/popup-card.php)
---------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('popup-modal');
  var closeBtn = document.getElementById('popup-close-btn');
  var dismissBtn = document.getElementById('popup-dismiss-btn');
  var form = document.getElementById('popup-newsletter-form');

  if (!modal) return;

  // Show modal automatically after 1.2s if not dismissed during current session
  var isDismissed = sessionStorage.getItem('sorwatom_popup_dismissed');
  if (!isDismissed) {
    setTimeout(function () {
      modal.classList.add('is-visible');
      modal.setAttribute('aria-hidden', 'false');
    }, 1200);
  }

  function hidePopup() {
    modal.classList.remove('is-visible');
    modal.setAttribute('aria-hidden', 'true');
    sessionStorage.setItem('sorwatom_popup_dismissed', 'true');
  }

  if (closeBtn) closeBtn.addEventListener('click', hidePopup);
  if (dismissBtn) dismissBtn.addEventListener('click', hidePopup);

  modal.addEventListener('click', function (e) {
    if (e.target === modal) {
      hidePopup();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('is-visible')) {
      hidePopup();
    }
  });

  // Handle Newsletter Submission via AJAX
  if (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      var msg = form.querySelector('.popup-newsletter-msg');
      var btn = form.querySelector('.popup-submit-btn');
      var input = form.querySelector('#popup-nl-email');
      var email = input.value.trim();

      var msgOk = form.dataset.msgOk;
      var msgAgain = form.dataset.msgAlready;
      var msgErr = form.dataset.msgErr;
      var msgBad = form.dataset.msgInvalid;

      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        msg.textContent = msgBad;
        msg.className = 'popup-newsletter-msg popup-newsletter-msg--err';
        return;
      }

      var origBtnText = btn.textContent;
      btn.disabled = true;
      btn.textContent = '…';

      try {
        var fd = new FormData(form);
        var res = await fetch(form.dataset.action, { method: 'POST', body: fd });
        var json = await res.json();
        msg.textContent = json.ok ? (json.already ? msgAgain : msgOk) : (json.message || msgErr);
        msg.className = 'popup-newsletter-msg popup-newsletter-msg--' + (json.ok ? 'ok' : 'err');

        if (json.ok) {
          input.value = '';
          btn.style.display = 'none';
          setTimeout(hidePopup, 2500);
        }
      } catch (_) {
        msg.textContent = msgErr;
        msg.className = 'popup-newsletter-msg popup-newsletter-msg--err';
      } finally {
        btn.disabled = false;
        if (btn.style.display !== 'none') btn.textContent = origBtnText;
      }
    });
  }
});


/* ----------------------------------------------------------------
   3. FOOTER NEWSLETTER FORM (partials/footer.php)
---------------------------------------------------------------- */
(function () {
  const form = document.getElementById('newsletter-form');
  if (!form) return;
  const msg      = form.querySelector('.footer-newsletter__msg');
  const btn      = form.querySelector('button[type="submit"]');
  const msgOk    = form.dataset.msgOk;
  const msgAgain = form.dataset.msgAlready;
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
      show(json.ok ? (json.already ? msgAgain : msgOk) : (json.message || msgErr), json.ok ? 'ok' : 'err');
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
