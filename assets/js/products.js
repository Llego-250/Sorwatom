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
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) { if (entry.isIntersecting) { entry.target.classList.add('revealed'); observer.unobserve(entry.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function (el) { observer.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('revealed'); });
  }
})();
