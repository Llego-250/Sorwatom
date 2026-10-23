<?php require_once __DIR__ . '/_lang.php'; ?>
<!doctype html>
<html lang="<?= htmlspecialchars($LANG_CODE) ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= htmlspecialchars($page_description ?? __r('meta.default_desc')) ?>">

  <title><?= htmlspecialchars($page_title ?? __r('meta.default_title')) ?></title>

  <?php
    // One address per page and language: English at the plain URL, Kinyarwanda at
    // ?lang=rw. Error pages and pages like unsubscribe stay out of search results.
    $seo_base  = defined('SITE_URL') ? SITE_URL : 'https://' . $_SERVER['HTTP_HOST'];
    $seo_path  = strtok($_SERVER['REQUEST_URI'], '?');
    $seo_url   = static fn(string $lang): string => $seo_base . $seo_path . ($lang === 'en' ? '' : '?lang=' . $lang);
    $canonical = $seo_url($LANG_CODE);
    $noindex   = !empty($page_noindex) || http_response_code() >= 400;
    $locales   = ['en' => 'en_GB', 'rw' => 'rw_RW'];
  ?>
  <?php if ($noindex): ?>
  <meta name="robots" content="noindex">
  <?php else: ?>
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
  <?php foreach ($allowed as $code): ?>
  <link rel="alternate" hreflang="<?= $code ?>" href="<?= htmlspecialchars($seo_url($code)) ?>">
  <?php endforeach; ?>
  <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($seo_url('en')) ?>">
  <?php endif; ?>

  <!-- Open Graph (link previews on WhatsApp, Facebook, X…) -->
  <meta property="og:type"        content="<?= htmlspecialchars($og_type        ?? 'website') ?>">
  <meta property="og:title"       content="<?= htmlspecialchars($og_title       ?? $page_title ?? 'Sorwatom') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($og_description ?? $page_description ?? '') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($og_image       ?? $seo_base . '/assets/img/slider/hero-tomato.jpg') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($og_url         ?? $canonical) ?>">
  <meta property="og:site_name"   content="Sorwatom">
  <meta property="og:locale"      content="<?= $locales[$LANG_CODE] ?? 'en_GB' ?>">
  <?php foreach ($locales as $code => $locale): if ($code !== $LANG_CODE): ?>
  <meta property="og:locale:alternate" content="<?= $locale ?>">
  <?php endif; endforeach; ?>
  <meta name="twitter:card"       content="summary_large_image">

  <?php
    // Structured data: who Sorwatom is (home page) and where a page sits (breadcrumb)
    $json_ld_items = [];
    if (($current_page ?? '') === 'home') {
        $json_ld_items[] = [
            '@type'        => 'Organization',
            '@id'          => $seo_base . '/#organization',
            'name'         => 'Sorwatom',
            'legalName'    => 'Sorwatom Agribusiness',
            'url'          => $seo_base . '/',
            'logo'         => $seo_base . '/assets/img/logo.png',
            'image'        => $seo_base . '/assets/img/slider/hero-tomato.jpg',
            'description'  => __r('meta.home.desc'),
            'foundingDate' => '1984',
            'address'      => ['@type' => 'PostalAddress', 'streetAddress' => 'Ndera - Mulindi',
                               'addressLocality' => 'Kigali', 'addressCountry' => 'RW'],
            'contactPoint' => [['@type' => 'ContactPoint', 'contactType' => 'customer service',
                                'telephone' => '+250787160000', 'email' => 'info@sorwatom.com',
                                'areaServed' => ['RW', 'BI', 'CD'], 'availableLanguage' => ['English', 'Kinyarwanda']]],
            'sameAs'       => ['https://www.instagram.com/sorwatom_rw/', 'https://www.tiktok.com/@sorwatom_rw',
                               'https://www.facebook.com/SORWATOM/', 'https://x.com/sorwatom_rw'],
        ];
        $json_ld_items[] = [
            '@type'      => 'WebSite',
            '@id'        => $seo_base . '/#website',
            'url'        => $seo_base . '/',
            'name'       => 'Sorwatom',
            'inLanguage' => $allowed,
            'publisher'  => ['@id' => $seo_base . '/#organization'],
        ];
    }
    // Pages with a breadcrumb set $breadcrumb = [[name, '/path'], …]
    if (!empty($breadcrumb)) {
        $json_ld_items[] = [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array_map(static fn($i, $crumb) => [
                '@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb[0], 'item' => $seo_base . $crumb[1],
            ], array_keys($breadcrumb), $breadcrumb),
        ];
    }
  ?>
  <?php if ($json_ld_items): ?>
  <script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@graph' => $json_ld_items], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
  <?php endif; ?>

  <!-- Hero image preloads — highest priority, before everything else -->
  <?php if (!empty($hero_img_mobile)): $hm = htmlspecialchars($hero_img_mobile); ?>
  <link rel="preload" as="image" fetchpriority="high" media="(max-width: 767px)"
    href="/assets/img/slider/Mobile/<?= $hm ?>">
  <?php endif; ?>
  <?php if (!empty($hero_img)): $h = htmlspecialchars($hero_img); ?>
  <link rel="preload" as="image" fetchpriority="high" media="(min-width: 768px)"
    href="/assets/img/slider/<?= $h ?>.webp">
  <?php endif; ?>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/img/favicon.png">
  <link rel="apple-touch-icon" href="/assets/img/favicon.png">

  <!-- Design system — load order is fixed -->
  <link rel="stylesheet" href="/assets/css/fonts.css">
  <link rel="stylesheet" href="/assets/css/tokens.css">
  <link rel="stylesheet" href="/assets/css/reset.css">
  <link rel="stylesheet" href="/assets/css/global.css">
  <link rel="stylesheet" href="/assets/css/layout.css">
  <link rel="stylesheet" href="/assets/css/components.css">

  <!-- Per-page stylesheets -->
  <?php foreach ($page_css ?? [] as $css): ?>
  <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($css) ?>">
  <?php endforeach; ?>

</head>
