'use strict';

/* Tomato paste gallery — each pack size swaps the image, counter, description, note and feature list */
(function () {
    function switchImg(thumb) {
        const thumbs = document.querySelectorAll('.product-thumb');
        const mainImg = document.getElementById('mainProductImg');
        const counter = document.getElementById('galleryCounter');

        thumbs.forEach(function (t) {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });

        thumb.classList.add('active');
        thumb.setAttribute('aria-selected', 'true');

        const nextSrc = thumb.getAttribute('data-img');
        const nextAlt = thumb.getAttribute('data-alt') || mainImg.dataset.defaultAlt || '';
        const index = thumb.getAttribute('data-index') || '1';
        const total = String(thumbs.length).padStart(2, '0');

        if (counter) {
            counter.textContent = String(index).padStart(2, '0') + ' / ' + total;
        }

        /* Each pack has its own description. */
        const desc = document.getElementById('productDesc');
        const nextDesc = thumb.getAttribute('data-desc');
        if (desc && nextDesc) {
            desc.textContent = nextDesc;
        }

        /* ...and its own highlight and feature list. */
        const note = document.getElementById('productNote');
        const nextNote = thumb.getAttribute('data-note');
        if (note && nextNote) {
            note.textContent = nextNote;
        }
        document.querySelectorAll('.product-features[data-pack]').forEach(function (list) {
            list.hidden = list.getAttribute('data-pack') !== String(index);
        });

        mainImg.style.opacity = '0';

        setTimeout(function () {
            mainImg.src = nextSrc;
            mainImg.alt = nextAlt;

            if (mainImg.decode) {
                mainImg.decode().catch(function () {}).finally(function () {
                    mainImg.style.opacity = '1';
                });
            } else {
                mainImg.style.opacity = '1';
            }
        }, 120);
    }

    document.querySelectorAll('.product-thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () { switchImg(thumb); });
    });

    /* Keyboard support for the product thumbnails. */
    document.addEventListener('keydown', function (event) {
        const active = document.activeElement;
        if (!active || !active.classList.contains('product-thumb')) return;

        const thumbs = Array.from(document.querySelectorAll('.product-thumb'));
        const current = thumbs.indexOf(active);
        if (current < 0) return;

        let next = current;

        if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
            next = (current + 1) % thumbs.length;
        } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
            next = (current - 1 + thumbs.length) % thumbs.length;
        } else {
            return;
        }

        event.preventDefault();
        thumbs[next].focus();
        switchImg(thumbs[next]);
    });

    /* Ensure the first gallery item is correctly selected on initial load. */
    const thumbs = document.querySelectorAll('.product-thumb');
    const mainImg = document.getElementById('mainProductImg');

    if (!thumbs.length || !mainImg) return;

    thumbs.forEach(function (thumb, index) {
        thumb.classList.toggle('active', index === 0);
        thumb.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
    });

    mainImg.src = thumbs[0].getAttribute('data-img');

    const desc = document.getElementById('productDesc');
    const firstDesc = thumbs[0].getAttribute('data-desc');
    if (desc && firstDesc) {
        desc.textContent = firstDesc;
    }

    const counter = document.getElementById('galleryCounter');
    if (counter) {
        counter.textContent = '01 / ' + String(thumbs.length).padStart(2, '0');
    }
})();
