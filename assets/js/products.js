'use strict';

/* Collection page — category filter pills and scroll reveal */
(function () {
  var pills = document.querySelectorAll('.filter-pill');
  var cards = document.querySelectorAll('.product-card[data-category]');
  function applyFilter(filter) {
    cards.forEach(function (card) { card.dataset.hidden = (filter === 'all' || card.dataset.category === filter) ? 'false' : 'true'; });
  }
  pills.forEach(function (pill) {
    pill.addEventListener('click', function () {
      pills.forEach(function (p) { p.classList.remove('active'); p.setAttribute('aria-pressed','false'); });
      pill.classList.add('active'); pill.setAttribute('aria-pressed','true');
      applyFilter(pill.dataset.filter);
    });
  });
  /* Product images lean toward the mouse — see "Image stage" in products.css */
  if (window.matchMedia('(hover: hover) and (pointer: fine)').matches &&
      !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.product-card__img-wrap').forEach(function (stage) {
      stage.addEventListener('pointermove', function (ev) {
        var r = stage.getBoundingClientRect();
        stage.style.setProperty('--mx', ((ev.clientX - r.left) / r.width * 2 - 1).toFixed(3));
        stage.style.setProperty('--my', ((ev.clientY - r.top) / r.height * 2 - 1).toFixed(3));
      });
      stage.addEventListener('pointerleave', function () {
        stage.style.removeProperty('--mx');
        stage.style.removeProperty('--my');
      });
    });
  }
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) { if (entry.isIntersecting) { entry.target.classList.add('revealed'); observer.unobserve(entry.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function (el) { observer.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('revealed'); });
  }
})();
