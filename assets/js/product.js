'use strict';

/* Product gallery (vinegar, ketchup, masala) — thumbnails and pack-format buttons swap the main image */
(function () {
  var mainImg = document.getElementById('mainProductImg');
  var thumbs = document.querySelectorAll('.product-thumb');

  function switchImg(thumb) {
    thumbs.forEach(function (t) { t.classList.remove('active'); });
    thumb.classList.add('active');
    if (!mainImg) return;
    mainImg.style.opacity = '0';
    setTimeout(function () {
      mainImg.src = thumb.getAttribute('data-img');
      mainImg.alt = thumb.getAttribute('data-alt');
      mainImg.style.opacity = '1';
    }, 150);
  }

  thumbs.forEach(function (thumb) {
    thumb.addEventListener('click', function () { switchImg(thumb); });
  });

  var formatButtons = document.querySelectorAll('.sp-variant');
  formatButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      formatButtons.forEach(function (btn) { btn.classList.remove('is-selected'); });
      button.classList.add('is-selected');
      if (!mainImg) return;
      mainImg.style.opacity = '0';
      setTimeout(function () {
        mainImg.src = button.dataset.img;
        mainImg.alt = button.dataset.name || mainImg.dataset.defaultAlt || '';
        mainImg.style.opacity = '1';
      }, 110);
    });
  });
})();
