'use strict';

/* Share bar — copy the article link, then briefly confirm on the button */
(function () {
  document.querySelectorAll('.share-btn--copy').forEach(function (btn) {
    btn.addEventListener('click', function () {
      navigator.clipboard.writeText(window.location.href).then(function () {
        var svg = btn.querySelector('svg').outerHTML;
        function setLabel(text) {
          btn.innerHTML = svg;
          btn.append(' ' + text);
        }
        setLabel(btn.dataset.copiedLabel);
        setTimeout(function () { setLabel(btn.dataset.copyLabel); }, 2500);
      });
    });
  });
})();
