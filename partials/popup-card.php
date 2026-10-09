<!-- Community & Newsletter Popup Modal -->
<div class="popup-modal-backdrop" id="popup-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="popup-title">
  <div class="popup-card-wrapper">
    <div class="popup-card">

      <!-- Close button -->
      <button type="button" class="popup-close-btn" id="popup-close-btn" aria-label="<?= __t('popup.close') ?>">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>

      <!-- Badge & Title Header -->
      <div class="popup-header">
        <span class="popup-badge"><?= __t('popup.badge') ?></span>
        <h2 class="popup-title" id="popup-title"><?= __r('popup.title') ?></h2>
        <p class="popup-subtitle"><?= __t('popup.subtitle') ?></p>
      </div>

      <!-- Popup Newsletter Form -->
      <div class="popup-newsletter-box">
        <h3 class="popup-section-label"><?= __t('popup.newsletter_title') ?></h3>
        <form
          class="popup-newsletter-form"
          id="popup-newsletter-form"
          novalidate
          data-action="/newsletter"
          data-msg-ok="<?= __t('footer.newsletter.success') ?>"
          data-msg-err="<?= __t('footer.newsletter.error') ?>"
          data-msg-invalid="<?= __t('footer.newsletter.error_email') ?>">
          <!-- Honeypot -->
          <input type="text" name="hp_website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">
          <div class="popup-input-group">
            <svg class="popup-input-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <input
              type="email"
              id="popup-nl-email"
              name="email"
              placeholder="<?= __t('footer.newsletter.placeholder') ?>"
              required
              autocomplete="email">
            <button type="submit" class="popup-submit-btn"><?= __t('footer.newsletter.btn') ?></button>
          </div>
          <p class="popup-newsletter-msg" role="status" aria-live="polite"></p>
        </form>
      </div>

      <!-- Social Media Section -->
      <div class="popup-social-box">
        <h3 class="popup-section-label"><?= __t('popup.social_title') ?></h3>
        <div class="popup-social-grid">
          <a href="https://www.instagram.com/sorwatom_rw/" target="_blank" rel="noopener noreferrer" class="popup-social-card insta" aria-label="Sorwatom on Instagram">
            <div class="popup-social-icon">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
            </div>
            <span>Instagram</span>
          </a>

          <a href="https://www.linkedin.com/in/sorwatom-ltd-6b2a48177/" target="_blank" rel="noopener noreferrer" class="popup-social-card linkedin" aria-label="Sorwatom on LinkedIn">
            <div class="popup-social-icon">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                <rect x="2" y="9" width="4" height="12"></rect>
                <circle cx="4" cy="4" r="2"></circle>
              </svg>
            </div>
            <span>LinkedIn</span>
          </a>

          <a href="https://www.facebook.com/SORWATOM/" target="_blank" rel="noopener noreferrer" class="popup-social-card fb" aria-label="Sorwatom on Facebook">
            <div class="popup-social-icon">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
              </svg>
            </div>
            <span>Facebook</span>
          </a>

          <a href="https://www.X.com/sorwatom/" target="_blank" rel="noopener noreferrer" class="popup-social-card twitter" aria-label="Sorwatom on Twitter (X)">
            <div class="popup-social-icon">
              <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
            </div>
            <span>Twitter (X)</span>
          </a>
        </div>
      </div>

      <!-- Footer action -->
      <div class="popup-footer-actions">
        <button type="button" class="popup-dismiss-link" id="popup-dismiss-btn"><?= __t('popup.maybe_later') ?></button>
      </div>

    </div>
  </div>
</div>

<style>
.popup-modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 10000;
  background: rgba(8, 16, 11, 0.75);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.25rem;
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1), visibility 0s linear 0.35s;
}
.popup-modal-backdrop.is-visible {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
  transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1), visibility 0s linear 0s;
}

.popup-card-wrapper {
  width: 100%;
  max-width: 520px;
  transform: translateY(24px) scale(0.96);
  transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.popup-modal-backdrop.is-visible .popup-card-wrapper {
  transform: translateY(0) scale(1);
}

.popup-card {
  position: relative;
  background: linear-gradient(145deg, #132417 0%, #0a160d 100%);
  border: 1px solid rgba(200, 146, 58, 0.28);
  box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.6), 0 0 30px rgba(200, 146, 58, 0.08);
  border-radius: 20px;
  padding: 2.25rem 2rem 1.75rem;
  color: #ffffff;
  overflow: hidden;
}

.popup-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #c8923a, #e6b86a, #c8923a);
}

.popup-close-btn {
  position: absolute;
  top: 1.25rem;
  right: 1.25rem;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.12);
  color: rgba(255, 255, 255, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}
.popup-close-btn:hover {
  background: rgba(200, 146, 58, 0.25);
  border-color: var(--col-accent, #c8923a);
  color: #ffffff;
  transform: rotate(90deg);
}

.popup-header {
  text-align: center;
  margin-bottom: 1.5rem;
}
.popup-badge {
  display: inline-block;
  padding: 4px 14px;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--col-accent, #c8923a);
  background: rgba(200, 146, 58, 0.12);
  border: 1px solid rgba(200, 146, 58, 0.3);
  border-radius: 50px;
  margin-bottom: 0.75rem;
}
.popup-title {
  font-family: var(--font-display, serif);
  font-size: clamp(1.5rem, 4vw, 1.85rem);
  font-weight: 600;
  color: #ffffff;
  line-height: 1.25;
  margin: 0 0 0.5rem;
}
.popup-title em {
  font-style: italic;
  color: var(--col-accent, #c8923a);
}
.popup-subtitle {
  font-size: 0.88rem;
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.5;
  margin: 0 auto;
  max-width: 440px;
}

.popup-section-label {
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.5);
  margin-bottom: 0.65rem;
}

.popup-newsletter-box {
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 12px;
  padding: 1.1rem;
  margin-bottom: 1.25rem;
}

.popup-input-group {
  position: relative;
  display: flex;
  align-items: center;
  gap: 0;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  overflow: hidden;
  background: rgba(0, 0, 0, 0.25);
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.popup-input-group:focus-within {
  border-color: var(--col-accent, #c8923a);
  box-shadow: 0 0 0 3px rgba(200, 146, 58, 0.15);
}
.popup-input-icon {
  position: absolute;
  left: 12px;
  color: rgba(255, 255, 255, 0.4);
  pointer-events: none;
}
.popup-input-group input[type="email"] {
  flex: 1;
  width: 100%;
  padding: 11px 12px 11px 40px;
  background: transparent;
  border: none;
  color: #ffffff;
  font-size: 0.88rem;
  outline: none;
}
.popup-input-group input[type="email"]::placeholder {
  color: rgba(255, 255, 255, 0.38);
}

.popup-submit-btn {
  padding: 11px 18px;
  background: var(--col-accent, #c8923a);
  color: #ffffff;
  border: none;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.03em;
  cursor: pointer;
  white-space: nowrap;
  transition: background 0.2s ease;
}
.popup-submit-btn:hover {
  background: #dba54d;
}

.popup-newsletter-msg {
  margin: 8px 0 0;
  font-size: 0.82rem;
  min-height: 1.2em;
  line-height: 1.4;
  text-align: left;
}
.popup-newsletter-msg--ok { color: #6ee7b7; }
.popup-newsletter-msg--err { color: #fca5a5; }

.popup-social-box {
  margin-bottom: 1.25rem;
}
.popup-social-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0.65rem;
}

.popup-social-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 6px;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 10px;
  color: rgba(255, 255, 255, 0.85);
  text-decoration: none;
  font-size: 0.72rem;
  font-weight: 500;
  transition: all 0.22s ease;
}
.popup-social-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s ease;
}

.popup-social-card:hover {
  transform: translateY(-2px);
  background: rgba(200, 146, 58, 0.15);
  border-color: rgba(200, 146, 58, 0.4);
  color: #ffffff;
}
.popup-social-card:hover .popup-social-icon {
  transform: scale(1.12);
}

.popup-social-card.insta:hover { border-color: #e1306c; background: rgba(225, 48, 108, 0.15); }
.popup-social-card.linkedin:hover { border-color: #0077b5; background: rgba(0, 119, 181, 0.15); }
.popup-social-card.fb:hover { border-color: #1877f2; background: rgba(24, 119, 242, 0.15); }
.popup-social-card.twitter:hover { border-color: #ffffff; background: rgba(255, 255, 255, 0.12); }

.popup-footer-actions {
  text-align: center;
}
.popup-dismiss-link {
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.5);
  font-size: 0.8rem;
  text-decoration: underline;
  cursor: pointer;
  transition: color 0.2s ease;
  padding: 4px 8px;
}
.popup-dismiss-link:hover {
  color: rgba(255, 255, 255, 0.85);
}

@media (max-width: 480px) {
  .popup-card { padding: 1.75rem 1.25rem 1.25rem; }
  .popup-social-grid { grid-template-columns: repeat(2, 1fr); }
  .popup-input-group { flex-direction: column; border-radius: 8px; overflow: hidden; }
  .popup-input-icon { top: 12px; }
  .popup-input-group input[type="email"] { padding: 10px 12px 10px 38px; }
  .popup-submit-btn { width: 100%; border-radius: 0; padding: 10px; }
}
</style>

<script>
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
        msg.textContent = json.ok ? msgOk : (json.message || msgErr);
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
</script>
