<?php /* Visible breadcrumb under a product hero, from the page's $breadcrumb = [[name, '/path'], …] (_head.php also turns it into structured data) */ ?>
<nav class="sp-breadcrumb" aria-label="<?= __t('a11y.breadcrumb') ?>">
    <div class="container sp-breadcrumb__inner">
        <?php foreach ($breadcrumb as $i => [$crumb_name, $crumb_url]): ?>
        <?php if ($i > 0): ?><span class="sp-breadcrumb__sep" aria-hidden="true">/</span><?php endif; ?>
        <?php if ($i === count($breadcrumb) - 1): ?>
        <span class="sp-breadcrumb__current" aria-current="page"><?= htmlspecialchars($crumb_name) ?></span>
        <?php else: ?>
        <a href="<?= htmlspecialchars($crumb_url) ?>"><?= htmlspecialchars($crumb_name) ?></a>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</nav>
