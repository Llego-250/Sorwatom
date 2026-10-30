'use strict';

document.addEventListener('DOMContentLoaded', () => {

  /* ----------------------------------------------------------------
     1. NAV SCROLL BEHAVIOUR
  ---------------------------------------------------------------- */
  const nav = document.getElementById('site-nav');
  window.addEventListener('scroll', () => {
    nav?.classList.toggle('scrolled', window.scrollY > 60);
  }, { passive: true });


  /* ----------------------------------------------------------------
     2. MOBILE NAV TOGGLE
  ---------------------------------------------------------------- */
  const toggle = document.querySelector('.nav-toggle');
  const mobileNav = document.getElementById('mobile-nav');
  const mobileLinks = mobileNav?.querySelectorAll('a');

  function closeNav() {
    toggle?.setAttribute('aria-expanded', 'false');
    mobileNav?.setAttribute('aria-hidden', 'true');
    mobileNav?.classList.remove('open');
    toggle?.setAttribute('aria-label', toggle.dataset.labelOpen || 'Open menu');
    document.body.style.overflow = '';
  }

  toggle?.addEventListener('click', () => {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!isOpen));
    mobileNav?.setAttribute('aria-hidden', String(isOpen));
    mobileNav?.classList.toggle('open', !isOpen);
    toggle.setAttribute('aria-label', !isOpen
      ? (toggle.dataset.labelClose || 'Close menu')
      : (toggle.dataset.labelOpen || 'Open menu'));
    document.body.style.overflow = !isOpen ? 'hidden' : '';
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && mobileNav?.classList.contains('open')) {
      closeNav();
    }
  });

  mobileLinks?.forEach(link => link.addEventListener('click', closeNav));


  /* ----------------------------------------------------------------
     3. SCROLL REVEAL (IntersectionObserver)
  ---------------------------------------------------------------- */
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));


  /* ----------------------------------------------------------------
     4. PRODUCT FILTER (products page only)
  ---------------------------------------------------------------- */
  const filterBtns = document.querySelectorAll('.filter-pill');
  const productCards = document.querySelectorAll('.product-card[data-category]');

  if (filterBtns.length && productCards.length) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        productCards.forEach(card => {
          const show = filter === 'all' || card.dataset.category === filter;
          card.dataset.hidden = show ? 'false' : 'true';
          card.setAttribute('aria-hidden', String(!show));
        });
      });
    });
  }


  /* ----------------------------------------------------------------
     5. FAQ ACCORDION
  ---------------------------------------------------------------- */
  const accordionItems = document.querySelectorAll('.accordion__item');

  accordionItems.forEach(item => {
    const trigger = item.querySelector('.accordion__trigger');
    const body = item.querySelector('.accordion__body');

    trigger?.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');

      // Close all
      accordionItems.forEach(i => {
        i.classList.remove('open');
        i.querySelector('.accordion__trigger')?.setAttribute('aria-expanded', 'false');
        const b = i.querySelector('.accordion__body');
        if (b) b.style.maxHeight = null;
      });

      // Open clicked (if was closed)
      if (!isOpen) {
        item.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
        if (body) body.style.maxHeight = body.scrollHeight + 'px';
      }
    });
  });


  /* ----------------------------------------------------------------
     6. INQUIRY TYPE PILLS (contact page)
  ---------------------------------------------------------------- */
  const inquiryPills = document.querySelectorAll('.inquiry-pill');
  const inquiryInput = document.getElementById('inquiry_type');

  inquiryPills.forEach(pill => {
    pill.addEventListener('click', () => {
      inquiryPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      if (inquiryInput) inquiryInput.value = pill.dataset.type;
    });
  });


  /* ----------------------------------------------------------------
     7. PRODUCT IMAGE LEAN (home and collection product cards)
     Sets --mx / --my (-1 to 1) for the image stage in components.css.
  ---------------------------------------------------------------- */
  if (matchMedia('(hover: hover) and (pointer: fine)').matches &&
      !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.product-card--signature .product-card__img-wrap').forEach(stage => {
      stage.addEventListener('pointermove', ev => {
        const r = stage.getBoundingClientRect();
        stage.style.setProperty('--mx', ((ev.clientX - r.left) / r.width * 2 - 1).toFixed(3));
        stage.style.setProperty('--my', ((ev.clientY - r.top) / r.height * 2 - 1).toFixed(3));
      });
      stage.addEventListener('pointerleave', () => {
        stage.style.removeProperty('--mx');
        stage.style.removeProperty('--my');
      });
    });
  }

});
