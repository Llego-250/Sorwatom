'use strict';

/* Hero stats — count up when they scroll into view */
(function () {
  var els = document.querySelectorAll('.stats-bar__value[data-count]');
  if (!els.length) return;
  function animateCount(el) {
    var target = parseInt(el.dataset.count, 10), suffix = el.dataset.suffix || '', duration = 1600, start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / duration, 1), ease = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(ease * target) + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) { if (e.isIntersecting) { animateCount(e.target); io.unobserve(e.target); } });
    }, { threshold: 0.5 });
    els.forEach(function (el) { io.observe(el); });
  } else {
    els.forEach(function (el) { el.textContent = el.dataset.count + (el.dataset.suffix || ''); });
  }
})();

/* Certifications marquee — nudge a popover back inside the window when its logo sits near an edge */
(function () {
  document.querySelectorAll('.cert-logo-wrap').forEach(function (wrap) {
    var tip = wrap.querySelector('.cert-logo__popover');
    if (!tip) return;
    function fit() {
      var r = wrap.getBoundingClientRect(), half = tip.offsetWidth / 2, gap = 16;
      var centre = r.left + r.width / 2, vw = document.documentElement.clientWidth, shift = 0;
      if (centre - half < gap) shift = gap - (centre - half);
      else if (centre + half > vw - gap) shift = vw - gap - (centre + half);
      tip.style.setProperty('--shift', shift + 'px');
    }
    wrap.addEventListener('mouseenter', fit);
    wrap.addEventListener('focusin', fit);
  });
})();
