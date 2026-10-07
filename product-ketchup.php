<?php
$page_title       = 'Heirloom Ketchup — Sorwatom';
$page_description = 'Sorwatom Heirloom Tomato Ketchup — made from vine-ripened tomatoes with no artificial colours or high-fructose corn syrup. Available in 350g and 700g glass bottles.';
$page_css         = ['pages/products.css'];
$body_class       = 'product-page product-ketchup';
$current_page     = 'products';
include 'partials/_head.php';

$gallerySources = ['assets/img/products/ketchup'];
$galleryImages = [];
foreach ($gallerySources as $dir) {
    $matches = glob($dir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
    if ($matches) {
        foreach ($matches as $match) {
            $galleryImages[] = $match;
        }
    }
}
if (empty($galleryImages)) {
    $galleryImages = ['assets/img/products/ketchup.png'];
}
$mainGalleryImage = $galleryImages[0];
?>
<style>
/* ── Product Detail Layout ─────────────────────────────────────────── */
.product-hero {
    background: #1a3a2e;
    padding: 80px 0 60px;
    text-align: center;
    color: #fff;
}
.product-hero .eyebrow {
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #a8c5b0;
    margin-bottom: 14px;
    display: block;
}
.product-hero h1 {
    font-size: 42px;
    font-weight: 300;
    color: #fff;
    margin: 0;
    line-height: 1.2;
}
.product-hero h1 em {
    font-style: normal;
    color: #7ecba0;
}
.product-detail {
    padding: 64px 0 80px;
    background: #fff;
}
.product-detail__grid {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: 56px;
    align-items: start;
}
@media (max-width: 768px) {
    .product-detail__grid { grid-template-columns: 1fr; gap: 32px; }
    .product-hero h1 { font-size: 30px; }
}
.product-img-main {
    position: relative;
    background: #f5f7f5;
    border-radius: 28px;
    padding: 34px;
    aspect-ratio: 1 / .88;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 1px solid #e4ebe6;
    box-shadow: 0 18px 55px rgba(13, 48, 34, .10);
}
.product-img-main img.product-main-img {
    width: auto;
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: opacity 0.25s ease;
    filter: drop-shadow(0 20px 22px rgba(20, 54, 38, .14));
}
.product-thumb-strip {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-top: 16px;
}
.product-thumb {
    position: relative;
    min-height: 126px;
    background: #f5f7f5;
    border-radius: 16px;
    padding: 12px;
    border: 1.5px solid #e4ebe6;
    cursor: pointer;
    transition: border-color 0.2s, transform 0.15s;
    text-align: center;
}
.product-thumb:hover { transform: translateY(-2px); }
.product-thumb.active { border-color: #1a7a50; background: #fff; box-shadow: 0 8px 24px rgba(23, 106, 74, .10); }
.product-thumb.active::after {
    content: "✓";
    position: absolute;
    top: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: #1d8159;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
}
.product-thumb img { width: 100%; height: 78px; object-fit: contain; display: block; margin: 0 auto 7px; }
.product-thumb span { font-size: 10px; font-weight: 800; color: #5c6962; letter-spacing: .09em; text-transform: uppercase; }
.sp-variant-heading {
    margin: 0 0 10px;
    color: #22362b;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
}
.sp-variant-list {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 27px;
}
.sp-variant {
    appearance: none;
    padding: 9px 13px;
    border: 1px solid #dfe8e2;
    border-radius: 10px;
    background: #fff;
    color: #3e4d45;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.sp-variant.is-selected {
    border-color: #1d8159;
    background: #f3f9f5;
    color: #124632;
}
.badge {
    display: inline-block;
    padding: 4px 14px;
    border: 1px solid #1a7a50;
    color: #1a7a50;
    border-radius: 20px;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 16px;
}
.product-detail__title {
    font-family: inherit;
    font-size: 22px;
    font-weight: 700;
    color: #1a2e1a;
    margin: 0 0 16px;
    line-height: 1.35;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.product-detail__desc {
    font-size: 15px;
    line-height: 1.75;
    color: #4a5a4a;
    margin-bottom: 24px;
}
.product-features {
    list-style: none;
    padding: 0;
    margin: 0 0 28px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.product-features li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #3a4a3a;
}
.feat-check {
    color: #1a7a50;
    font-weight: 700;
    font-size: 13px;
    min-width: 18px;
    text-align: center;
}
.product-detail__actions {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 32px;
}
.btn-primary-green {
    display: inline-block;
    padding: 13px 28px;
    background: #1a7a50;
    color: #fff !important;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    letter-spacing: 0.3px;
}
.btn-primary-green:hover { background: #155e3e; transform: translateY(-1px); color: #fff !important; }
.btn-outline-dark {
    display: inline-block;
    padding: 13px 28px;
    border: 2px solid #1a3a2e;
    color: #1a3a2e !important;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, color 0.2s, transform 0.15s;
    letter-spacing: 0.3px;
}
.btn-outline-dark:hover { background: #1a3a2e; color: #fff !important; transform: translateY(-1px); }
/* Related Products */
.related-section {
    padding: 56px 0 72px;
    background: #f8faf8;
}
.related-section .section-eyebrow {
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #1a7a50;
    margin-bottom: 32px;
    text-align: center;
    font-weight: 700;
}
.related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
@media (max-width: 768px) {
    .related-grid { grid-template-columns: 1fr; }
}
@media (min-width: 769px) and (max-width: 991px) {
    .related-grid { grid-template-columns: repeat(2, 1fr); }
}
.related-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    text-decoration: none;
    display: block;
    border: 1px solid #e8ede8;
    transition: box-shadow 0.2s, transform 0.2s;
}
.related-card:hover { box-shadow: 0 8px 28px rgba(26,58,46,0.12); transform: translateY(-3px); }
.related-card__img {
    background: #f5f7f5;
    padding: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 180px;
}
.related-card__img img { max-height: 130px; max-width: 100%; object-fit: contain; }
.related-card__body { padding: 18px 20px 20px; }
.related-card__label { font-size: 10px; letter-spacing: 2px; color: #1a7a50; text-transform: uppercase; font-weight: 700; margin-bottom: 6px; display: block; }
.related-card__name { font-size: 16px; font-weight: 700; color: #1a2e1a; margin: 0 0 8px; }
.related-card__link { font-size: 13px; color: #1a7a50; font-weight: 600; }
/* CTA Block */
.cta-block {
    padding: 64px 0;
    background: #1a3a2e;
    text-align: center;
    color: #fff;
}
.cta-block h2 { font-size: 28px; font-weight: 300; color: #fff; margin: 0 0 10px; }
.cta-block p { color: #a8c5b0; font-size: 15px; margin: 0 0 28px; }
</style>
<body class="<?php echo htmlspecialchars($body_class); ?>">

<?php include 'partials/nav.php'; ?>

<!-- Hero -->
<section class="hero hero--half product-hero" aria-label="Heirloom Ketchup">
    <img class="hero__bg" src="assets/img/slider/collection-flatlay.jpg" alt="" aria-hidden="true" fetchpriority="high" loading="eager">
    <div class="hero__content container">
        <span class="eyebrow eyebrow--light"><?= __t('pd.ketchup.hero_eyebrow') ?></span>
        <h1 class="hero__title"><?= __r('pd.ketchup.hero_title') ?></h1>
    </div>
</section>

<!-- Product Detail -->
<section class="product-detail">
    <div class="container">
        <div class="product-detail__grid">

            <!-- Gallery Column -->
            <div class="product-detail__gallery">
                <div class="product-img-main">
                    <img
                        id="mainProductImg"
                        src="<?= htmlspecialchars($mainGalleryImage) ?>"
                        alt="Sorwatom Heirloom Ketchup image"
                        loading="eager"
                        class="product-main-img">
                </div>
                <div class="product-thumb-strip">
                    <?php foreach ($galleryImages as $index => $galleryImage): ?>
                        <div class="product-thumb <?= $index === 0 ? 'active' : '' ?>" data-img="<?= htmlspecialchars($galleryImage) ?>" data-alt="Sorwatom Heirloom Ketchup product image" onclick="switchImg(this)">
                            <img src="<?= htmlspecialchars($galleryImage) ?>" alt="Sorwatom Heirloom Ketchup variant" loading="lazy" decoding="async">
                            <span>Variant <?= $index + 1 ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Info Column -->
            <div class="product-detail__info">
                <span class="badge"><?= __t('pd.ketchup.badge') ?></span>
                <h2 class="product-detail__title"><?= __t('pd.ketchup.title') ?></h2>
                <p class="product-detail__desc"><?= __t('pd.ketchup.desc') ?></p>
                <ul class="product-features">
                    <li><span class="feat-check">&#10003;</span> <?= __t('pd.ketchup.f1') ?></li>
                    <li><span class="feat-check">&#10003;</span> <?= __t('pd.ketchup.f2') ?></li>
                    <li><span class="feat-check">&#10003;</span> <?= __t('pd.ketchup.f3') ?></li>
                    <li><span class="feat-check">&#10003;</span> <?= __r('pd.ketchup.f4') ?></li>
                </ul>

                <div class="product-detail__actions">
                    <a href="/contact?inquiry=ketchup" class="btn-primary-green"><?= __t('pd.req_quote') ?></a>
                    <a href="/products" class="btn-outline-dark"><?= __t('pd.view_all') ?></a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Related Products -->
<section class="related-section">
    <div class="container">
        <p class="section-eyebrow"><?= __t('pd.related_eyebrow') ?></p>
        <div class="related-grid">

            <a href="/product-tomato-paste" class="related-card">
                <div class="related-card__img">
                    <img src="assets/img/products/tomatoes_paste_70g.png" alt="Sorwatom Tomato Paste" loading="lazy" decoding="async">
                </div>
                <div class="related-card__body">
                    <span class="related-card__label"><?= __t('pd.rel.tomato_label') ?></span>
                    <p class="related-card__name"><?= __t('pd.rel.tomato_name') ?></p>
                    <span class="related-card__link"><?= __t('pd.view_product') ?></span>
                </div>
            </a>

            <a href="/product-masala" class="related-card">
                <div class="related-card__img">
                    <img src="assets/img/products/masala.png" alt="Sorwatom Pilau Masala" loading="lazy" decoding="async">
                </div>
                <div class="related-card__body">
                    <span class="related-card__label"><?= __t('pd.rel.masala_label') ?></span>
                    <p class="related-card__name"><?= __t('pd.rel.masala_name') ?></p>
                    <span class="related-card__link"><?= __t('pd.view_product') ?></span>
                </div>
            </a>

            <a href="/product-vinegar" class="related-card">
                <div class="related-card__img">
                    <img src="assets/img/products/vinegar.png" alt="Sorwatom Pure Vinegar" loading="lazy" decoding="async">
                </div>
                <div class="related-card__body">
                    <span class="related-card__label"><?= __t('pd.rel.vinegar_label') ?></span>
                    <p class="related-card__name"><?= __t('pd.rel.vinegar_name') ?></p>
                    <span class="related-card__link"><?= __t('pd.view_product') ?></span>
                </div>
            </a>

        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<?php include 'partials/_scripts.php'; ?>
<script>
function switchImg(thumb) {
    var thumbs = document.querySelectorAll('.product-thumb');
    thumbs.forEach(function(t) { t.classList.remove('active'); });
    thumb.classList.add('active');
    var mainImg = document.getElementById('mainProductImg');
    if (!mainImg) return;
    mainImg.style.opacity = '0';
    setTimeout(function() {
        mainImg.src = thumb.getAttribute('data-img');
        mainImg.alt = thumb.getAttribute('data-alt');
        mainImg.style.opacity = '1';
    }, 150);
}

var formatButtons = document.querySelectorAll('.sp-variant');
formatButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        formatButtons.forEach(function(btn) { btn.classList.remove('is-selected'); });
        button.classList.add('is-selected');
        var mainImg = document.getElementById('mainProductImg');
        if (!mainImg) return;
        mainImg.style.opacity = '0';
        setTimeout(function() {
            mainImg.src = button.dataset.img;
            mainImg.alt = button.dataset.name || 'Sorwatom Heirloom Ketchup product image';
            mainImg.style.opacity = '1';
        }, 110);
    });
});
</script>
</body>
</html>
