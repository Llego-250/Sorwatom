<?php
$page_title       = 'Tomato Paste — Sorwatom';
$page_description = 'Sorwatom Double Concentrated Tomato Paste — made from sun-ripened Great Lakes tomatoes. Available in 50g Sachet, 70g Sachet, and 800g Tin.';
$page_css         = ['pages/products.css'];
$body_class       = 'product-page product-tomato-paste';
$current_page     = 'products';
include 'partials/_head.php';

$gallerySources = [
    'assets/img/products/tomatoes_paste_50g',
    'assets/img/products/tomatoes_paste_70g',
    'assets/img/products/tomatoes_paste_800g',
];

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
    $galleryImages = [
        'assets/img/products/tomatoes_paste_70g.png',
        'assets/img/products/tomatoes_paste_50g.png',
        'assets/img/products/tomatoes_paste_800g.png',
    ];
}

$mainGalleryImage = $galleryImages[0];
?>

<style>
/* =========================================================
   SORWATOM — TOMATO PASTE PRODUCT PAGE
   Drop-in page styling. Keeps existing site partials/routes.
   ========================================================= */

:root {
    --sp-green-950: #09251a;
    --sp-green-900: #0d3022;
    --sp-green-800: #124632;
    --sp-green-700: #176a4a;
    --sp-green-600: #1d8159;
    --sp-green-500: #29966a;
    --sp-green-100: #e8f5ee;
    --sp-green-50: #f3f9f5;
    --sp-cream: #f7f5ef;
    --sp-paper: #fbfcfa;
    --sp-ink: #17231d;
    --sp-muted: #65736b;
    --sp-line: #e4ebe6;
    --sp-gold: #c99a42;
    --sp-red: #d84a25;
    --sp-radius-lg: 28px;
    --sp-radius-md: 18px;
    --sp-shadow: 0 18px 55px rgba(13, 48, 34, .10);
}

.product-tomato-paste {
    background: #fff;
    color: var(--sp-ink);
}

.product-tomato-paste *,
.product-tomato-paste *::before,
.product-tomato-paste *::after {
    box-sizing: border-box;
}

.product-tomato-paste .container {
    width: min(1180px, calc(100% - 40px));
    margin-inline: auto;
}

/* ---------- Hero ---------- */

.product-tomato-paste .product-hero {
    position: relative;
    min-height: 390px;
    display: grid;
    place-items: center;
    overflow: hidden;
    isolation: isolate;
    background: var(--sp-green-950);
    color: #fff;
    text-align: center;
}

.product-tomato-paste .product-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    background:
        linear-gradient(180deg, rgba(5, 26, 18, .28), rgba(5, 26, 18, .78)),
        radial-gradient(circle at 50% 35%, rgba(71, 145, 103, .18), transparent 48%);
}

.product-tomato-paste .product-hero .hero__bg {
    position: absolute;
    inset: 0;
    z-index: -2;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 52%;
    filter: saturate(.88) contrast(.95);
    transform: scale(1.015);
}

.product-tomato-paste .product-hero .hero__content {
    padding: 88px 20px 72px;
}

.product-tomato-paste .product-hero .eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 17px;
    color: #cfe4d7;
    font-size: 10px;
    line-height: 1;
    letter-spacing: .28em;
    font-weight: 700;
    text-transform: uppercase;
}

.product-tomato-paste .product-hero .eyebrow::before,
.product-tomato-paste .product-hero .eyebrow::after {
    content: "";
    width: 24px;
    height: 1px;
    background: rgba(207, 228, 215, .5);
}

.product-tomato-paste .product-hero .hero__title {
    max-width: 800px;
    margin: 0 auto;
    color: #fff;
    font-size: clamp(38px, 5vw, 66px);
    line-height: .98;
    letter-spacing: -.035em;
    font-weight: 400;
    text-wrap: balance;
}

.product-tomato-paste .product-hero .hero__title em {
    color: #bfe5cc;
}

/* ---------- Breadcrumb ---------- */

.sp-breadcrumb {
    padding: 18px 0 0;
    background: #fff;
}

.sp-breadcrumb__inner {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #87948d;
    font-size: 12px;
}

.sp-breadcrumb a {
    color: #65736b;
    text-decoration: none;
}

.sp-breadcrumb a:hover {
    color: var(--sp-green-700);
}

.sp-breadcrumb__current {
    color: #1b2b23;
    font-weight: 600;
}

.sp-breadcrumb__sep {
    opacity: .45;
}

/* ---------- Main product section ---------- */

.product-tomato-paste .product-detail {
    padding: 34px 0 92px;
    background: #fff;
}

.product-tomato-paste .product-detail__grid {
    display: grid;
    grid-template-columns: minmax(0, 1.06fr) minmax(360px, .94fr);
    gap: clamp(42px, 6vw, 88px);
    align-items: start;
}

/* ---------- Gallery ---------- */

.sp-gallery {
    position: sticky;
    top: 24px;
}

.product-tomato-paste .product-img-main {
    position: relative;
    aspect-ratio: 1 / .88;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 34px;
    border: 1px solid var(--sp-line);
    border-radius: var(--sp-radius-lg);
    background:
        radial-gradient(circle at 50% 35%, rgba(255,255,255,.98), rgba(241,246,242,.92) 60%, rgba(231,239,233,.92)),
        var(--sp-paper);
    box-shadow: var(--sp-shadow);
}

.product-tomato-paste .product-img-main::before {
    content: "";
    position: absolute;
    width: 62%;
    aspect-ratio: 1;
    border-radius: 50%;
    background: rgba(38, 111, 77, .07);
    filter: blur(2px);
}

.product-tomato-paste img.product-main-img {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    transition: opacity .22s ease, transform .35s ease;
    filter: drop-shadow(0 20px 22px rgba(20, 54, 38, .14));
}

.product-tomato-paste .product-img-main:hover .product-main-img {
    transform: scale(1.025);
}

.sp-gallery__meta {
    position: absolute;
    left: 18px;
    right: 18px;
    bottom: 17px;
    z-index: 3;
    display: flex;
    justify-content: space-between;
    align-items: center;
    pointer-events: none;
}

.sp-gallery__counter,
.sp-gallery__hint {
    display: inline-flex;
    align-items: center;
    min-height: 30px;
    padding: 0 11px;
    border: 1px solid rgba(19, 59, 42, .10);
    border-radius: 999px;
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(10px);
    color: #536159;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .08em;
}

.product-tomato-paste .product-thumb-strip {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-top: 14px;
}

.product-tomato-paste .product-thumb {
    min-height: 126px;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px;
    border: 1.5px solid var(--sp-line);
    border-radius: 16px;
    background: #f7f9f7;
    cursor: pointer;
    text-align: center;
    transition: border-color .2s ease, transform .2s ease, background .2s ease, box-shadow .2s ease;
}

.product-tomato-paste .product-thumb:hover {
    transform: translateY(-2px);
    border-color: #a9cbb8;
    background: #fff;
}

.product-tomato-paste .product-thumb.active {
    border-color: var(--sp-green-600);
    background: #fff;
    box-shadow: 0 8px 24px rgba(23, 106, 74, .10);
}

.product-tomato-paste .product-thumb.active::after {
    content: "✓";
    position: absolute;
    top: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: var(--sp-green-600);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
}

.product-tomato-paste .product-thumb img {
    width: 100%;
    height: 78px;
    object-fit: contain;
    display: block;
    margin: 0 auto 7px;
}

.product-tomato-paste .product-thumb span {
    color: #5c6962;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .09em;
    text-transform: uppercase;
}

/* ---------- Product information ---------- */

.product-tomato-paste .product-detail__info {
    padding-top: 8px;
}

.product-tomato-paste .badge {
    display: inline-flex;
    align-items: center;
    min-height: 29px;
    padding: 0 12px;
    margin: 0 0 18px;
    border: 1px solid #afd2bd;
    border-radius: 999px;
    background: var(--sp-green-50);
    color: var(--sp-green-700);
    font-size: 9px;
    letter-spacing: .16em;
    text-transform: uppercase;
    font-weight: 800;
}

.product-tomato-paste .product-detail__title {
    max-width: 600px;
    margin: 0 0 17px;
    color: var(--sp-green-950);
    font-size: clamp(30px, 3vw, 45px);
    line-height: 1.03;
    letter-spacing: -.035em;
    font-weight: 700;
    text-transform: none;
}

.product-tomato-paste .product-detail__desc {
    max-width: 610px;
    margin: 0 0 25px;
    color: var(--sp-muted);
    font-size: 15px;
    line-height: 1.85;
}

.sp-quality-note {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 15px;
    margin-bottom: 24px;
    border: 1px solid #e5eee8;
    border-radius: 13px;
    background: #fbfdfb;
    color: #3f5147;
    font-size: 12px;
    line-height: 1.45;
}

.sp-quality-note__icon {
    flex: 0 0 auto;
    width: 31px;
    height: 31px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: var(--sp-green-100);
    color: var(--sp-green-700);
    font-size: 15px;
}

.product-tomato-paste .product-features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    padding: 0;
    margin: 0 0 28px;
    list-style: none;
}

.product-tomato-paste .product-features li {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 48px;
    padding: 10px 12px;
    border: 1px solid #edf1ee;
    border-radius: 12px;
    background: #fff;
    color: #3d4c44;
    font-size: 12px;
    line-height: 1.35;
}

.product-tomato-paste .product-features li::before {
    display: none;
}

.product-tomato-paste .product-features li .feat-check {
    flex: 0 0 auto;
    width: 23px;
    height: 23px;
    display: grid;
    place-items: center;
    margin: 0;
    min-width: 23px;
    border-radius: 50%;
    background: var(--sp-green-100);
    color: var(--sp-green-700);
    font-size: 11px;
    font-weight: 900;
}

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
    padding: 9px 13px;
    border: 1px solid #dfe8e2;
    border-radius: 10px;
    background: #fff;
    color: #3e4d45;
    font-size: 12px;
    font-weight: 700;
}

.sp-variant.is-selected {
    border-color: var(--sp-green-600);
    background: var(--sp-green-50);
    color: var(--sp-green-800);
}

.product-tomato-paste .product-detail__actions {
    display: flex;
    gap: 11px;
    flex-wrap: wrap;
    margin-top: 0;
}

.product-tomato-paste .btn-primary-green,
.product-tomato-paste .btn-outline-dark {
    min-height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 0 20px;
    border-radius: 11px;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    letter-spacing: .02em;
    transition: transform .18s ease, box-shadow .18s ease, background .18s ease, color .18s ease;
}

.product-tomato-paste .btn-primary-green {
    background: var(--sp-green-700);
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(23, 106, 74, .18);
}

.product-tomato-paste .btn-primary-green:hover {
    background: var(--sp-green-800);
    transform: translateY(-2px);
    color: #fff !important;
    box-shadow: 0 14px 28px rgba(23, 106, 74, .23);
}

.product-tomato-paste .btn-outline-dark {
    border: 1px solid #bfcac3;
    background: #fff;
    color: var(--sp-green-900) !important;
}

.product-tomato-paste .btn-outline-dark:hover {
    border-color: var(--sp-green-700);
    background: var(--sp-green-50);
    color: var(--sp-green-800) !important;
    transform: translateY(-2px);
}

/* ---------- Assurance strip ---------- */

.sp-assurance {
    margin-top: 48px;
    padding-top: 24px;
    border-top: 1px solid var(--sp-line);
}

.sp-assurance__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.sp-assurance__item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #536159;
    font-size: 11px;
    font-weight: 700;
}

.sp-assurance__icon {
    width: 32px;
    height: 32px;
    display: grid;
    place-items: center;
    flex: 0 0 auto;
    border-radius: 50%;
    background: var(--sp-green-50);
    color: var(--sp-green-700);
}

/* ---------- Related products ---------- */

.product-tomato-paste .related-section {
    padding: 78px 0 90px;
    background: #f5f8f5;
    border-top: 1px solid #edf1ed;
}

.product-tomato-paste .related-section .section-eyebrow {
    margin: 0 0 11px;
    color: var(--sp-green-700);
    font-size: 10px;
    letter-spacing: .25em;
    text-align: center;
    text-transform: uppercase;
    font-weight: 800;
}

.sp-related-heading {
    margin: 0 auto 34px;
    color: var(--sp-green-950);
    text-align: center;
    font-size: clamp(25px, 3vw, 35px);
    letter-spacing: -.025em;
    font-weight: 600;
}

.product-tomato-paste .related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

.product-tomato-paste .related-card {
    position: relative;
    display: block;
    overflow: hidden;
    border: 1px solid #e3eae4;
    border-radius: 20px;
    background: #fff;
    text-decoration: none;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.product-tomato-paste .related-card:hover {
    transform: translateY(-5px);
    border-color: #cbded1;
    box-shadow: 0 18px 40px rgba(18, 70, 50, .10);
}

.product-tomato-paste .related-card__img {
    height: 235px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px;
    background: linear-gradient(180deg, #fbfcfb, #f1f5f2);
}

.product-tomato-paste .related-card__img img {
    max-width: 100%;
    max-height: 185px;
    width: auto;
    object-fit: contain;
    filter: drop-shadow(0 12px 15px rgba(20, 50, 36, .10));
    transition: transform .25s ease;
}

.product-tomato-paste .related-card:hover .related-card__img img {
    transform: scale(1.04);
}

.product-tomato-paste .related-card__body {
    padding: 20px 20px 21px;
}

.product-tomato-paste .related-card__label {
    display: block;
    margin-bottom: 7px;
    color: var(--sp-green-600);
    font-size: 9px;
    letter-spacing: .16em;
    text-transform: uppercase;
    font-weight: 800;
}

.product-tomato-paste .related-card__name {
    margin: 0 0 13px;
    color: var(--sp-green-950);
    font-size: 17px;
    font-weight: 700;
}

.product-tomato-paste .related-card__link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: var(--sp-green-700);
    font-size: 11px;
    font-weight: 800;
}

.product-tomato-paste .related-card__link::after {
    content: "→";
    transition: transform .18s ease;
}

.product-tomato-paste .related-card:hover .related-card__link::after {
    transform: translateX(3px);
}

/* ---------- Responsive ---------- */

@media (max-width: 900px) {
    .product-tomato-paste .product-detail__grid {
        grid-template-columns: 1fr;
    }

    .sp-gallery {
        position: relative;
        top: auto;
    }

    .product-tomato-paste .product-detail__info {
        padding-top: 0;
    }

    .product-tomato-paste .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 680px) {
    .product-tomato-paste .container {
        width: min(100% - 28px, 1180px);
    }

    .product-tomato-paste .product-hero {
        min-height: 315px;
    }

    .product-tomato-paste .product-hero .hero__content {
        padding: 68px 15px 58px;
    }

    .product-tomato-paste .product-hero .hero__title {
        font-size: 40px;
    }

    .sp-breadcrumb {
        padding-top: 13px;
    }

    .product-tomato-paste .product-detail {
        padding: 24px 0 62px;
    }

    .product-tomato-paste .product-img-main {
        aspect-ratio: .96;
        padding: 20px;
        border-radius: 22px;
    }

    .product-tomato-paste .product-thumb-strip {
        gap: 8px;
    }

    .product-tomato-paste .product-thumb {
        min-height: 104px;
        padding: 8px;
        border-radius: 12px;
    }

    .product-tomato-paste .product-thumb img {
        height: 62px;
    }

    .product-tomato-paste .product-detail__title {
        font-size: 32px;
    }

    .product-tomato-paste .product-features {
        grid-template-columns: 1fr;
    }

    .sp-assurance__grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .product-tomato-paste .related-section {
        padding: 58px 0 65px;
    }

    .product-tomato-paste .related-grid {
        grid-template-columns: 1fr;
    }

    .product-tomato-paste .related-card__img {
        height: 215px;
    }

    .product-tomato-paste .product-detail__actions > * {
        flex: 1 1 100%;
    }
}

/* Respect reduced motion preferences. */
@media (prefers-reduced-motion: reduce) {
    .product-tomato-paste *,
    .product-tomato-paste *::before,
    .product-tomato-paste *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

<body class="<?php echo htmlspecialchars($body_class); ?>">

<?php include 'partials/nav.php'; ?>

<!-- Hero -->
<section class="hero hero--half product-hero" aria-label="Tomato Paste">
    <img
        class="hero__bg"
        src="assets/img/slider/collection-flatlay.jpg"
        alt=""
        aria-hidden="true"
        fetchpriority="high"
        loading="eager"
    >
    <div class="hero__content container">
        <span class="eyebrow eyebrow--light"><?= __t('pd.tomato.hero_eyebrow') ?></span>
        <h1 class="hero__title"><?= __r('pd.tomato.hero_title') ?></h1>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="sp-breadcrumb" aria-label="Breadcrumb">
    <div class="container sp-breadcrumb__inner">
        <a href="/">Home</a>
        <span class="sp-breadcrumb__sep">/</span>
        <a href="/products">Products</a>
        <span class="sp-breadcrumb__sep">/</span>
        <span class="sp-breadcrumb__current">Tomato Paste</span>
    </div>
</nav>

<!-- Product Detail -->
<section class="product-detail">
    <div class="container">
        <div class="product-detail__grid">

            <!-- Gallery -->
            <div class="product-detail__gallery sp-gallery">
                <div class="product-img-main">
                    <img
                        id="mainProductImg"
                        src="<?= htmlspecialchars($mainGalleryImage) ?>"
                        alt="Sorwatom Tomato Paste product image"
                        loading="eager"
                        class="product-main-img"
                    >

                    <div class="sp-gallery__meta">
                        <span class="sp-gallery__counter" id="galleryCounter">01 / <?= str_pad((string)count($galleryImages), 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="sp-gallery__hint">Click a pack to view</span>
                    </div>
                </div>

                <div class="product-thumb-strip" role="tablist" aria-label="Tomato paste package sizes">
                    <?php foreach ($galleryImages as $index => $galleryImage): ?>
                        <button
                            type="button"
                            class="product-thumb <?= $index === 0 ? 'active' : '' ?>"
                            data-img="<?= htmlspecialchars($galleryImage) ?>"
                            data-alt="Sorwatom Tomato Paste variant"
                            data-index="<?= $index + 1 ?>"
                            onclick="switchImg(this)"
                            role="tab"
                            aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                        >
                            <img
                                src="<?= htmlspecialchars($galleryImage) ?>"
                                alt="Sorwatom Tomato Paste variant"
                                loading="lazy"
                                decoding="async"
                            >
                            <span><?= __t('pd.tomato.thumb' . ($index + 1)) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Product Information -->
            <div class="product-detail__info">
                <span class="badge"><?= __t('pd.tomato.badge') ?></span>

                <h2 class="product-detail__title"><?= __t('pd.tomato.title') ?></h2>

                <p class="product-detail__desc"><?= __t('pd.tomato.desc') ?></p>

                <div class="sp-quality-note">
                    <span class="sp-quality-note__icon">✦</span>
                    <span>Rich tomato flavour, vibrant colour and a concentrated texture made for everyday cooking.</span>
                </div>

                <ul class="product-features">
                    <li><span class="feat-check">✓</span> <?= __t('pd.tomato.f1') ?></li>
                    <li><span class="feat-check">✓</span> <?= __t('pd.tomato.f2') ?></li>
                    <li><span class="feat-check">✓</span> <?= __t('pd.tomato.f3') ?></li>
                    <li><span class="feat-check">✓</span> <?= __t('pd.tomato.f4') ?></li>
                </ul>

                <div class="product-detail__actions">
                    <a href="/contact?inquiry=tomato-paste" class="btn-primary-green">
                        <?= __t('pd.req_quote') ?> <span aria-hidden="true">→</span>
                    </a>
                    <a href="/products" class="btn-outline-dark">
                        <?= __t('pd.view_all') ?>
                    </a>
                </div>

                <div class="sp-assurance">
                    <div class="sp-assurance__grid">
                        <div class="sp-assurance__item">
                            <span class="sp-assurance__icon">✓</span>
                            <span>Quality focused</span>
                        </div>
                        <div class="sp-assurance__item">
                            <span class="sp-assurance__icon">◎</span>
                            <span>Great Lakes sourcing</span>
                        </div>
                        <div class="sp-assurance__item">
                            <span class="sp-assurance__icon">◆</span>
                            <span>Made for global markets</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Related Products -->
<section class="related-section">
    <div class="container">
        <p class="section-eyebrow"><?= __t('pd.related_eyebrow') ?></p>
        <h2 class="sp-related-heading">Explore more from Sorwatom</h2>

        <div class="related-grid">

            <a href="/product-ketchup" class="related-card">
                <div class="related-card__img">
                    <img src="assets/img/products/ketchup.png" alt="Sorwatom Heirloom Ketchup" loading="lazy" decoding="async">
                </div>
                <div class="related-card__body">
                    <span class="related-card__label"><?= __t('pd.rel.ketchup_label') ?></span>
                    <p class="related-card__name"><?= __t('pd.rel.ketchup_name') ?></p>
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
    const nextAlt = thumb.getAttribute('data-alt') || 'Sorwatom Tomato Paste product image';
    const index = thumb.getAttribute('data-index') || '1';
    const total = String(thumbs.length).padStart(2, '0');

    if (counter) {
        counter.textContent = String(index).padStart(2, '0') + ' / ' + total;
    }

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
(function () {
    const thumbs = document.querySelectorAll('.product-thumb');
    const mainImg = document.getElementById('mainProductImg');

    if (!thumbs.length || !mainImg) return;

    thumbs.forEach(function (thumb, index) {
        thumb.classList.toggle('active', index === 0);
        thumb.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
    });

    mainImg.src = thumbs[0].getAttribute('data-img');

    const counter = document.getElementById('galleryCounter');
    if (counter) {
        counter.textContent = '01 / ' + String(thumbs.length).padStart(2, '0');
    }
})();
</script>

</body>
</html>
