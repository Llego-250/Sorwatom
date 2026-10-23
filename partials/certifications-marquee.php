<?php
$cert_groups   = require __DIR__ . '/certifications-data.php';
$cert_logo_dir = __DIR__ . '/../assets/img/Certifications logos/';

// Wide logos get a shorter box so every mark carries a similar visual weight
foreach ($cert_groups as &$group) {
    if (isset($group['logo']) && is_file($cert_logo_dir . $group['logo'])) {
        [$group['width'], $group['height']] = getimagesize($cert_logo_dir . $group['logo']);
        $group['scale'] = round(min(1, ($group['width'] / $group['height']) ** -0.4), 3);
    } else {
        unset($group['logo']);
    }
}
unset($group);

$e = fn(string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
?>
<section class="section certifications-section" aria-labelledby="certifications-heading">
  <div class="container certifications-section__header">
    <h2 id="certifications-heading" class="eyebrow"><?= __t('about.certifications.eyebrow') ?></h2>
  </div>
  <div class="cert-marquee" role="region" aria-label="<?= __t('about.certifications.eyebrow') ?>">
    <div class="cert-marquee__fade cert-marquee__fade--left" aria-hidden="true"></div>
    <div class="cert-marquee__fade cert-marquee__fade--right" aria-hidden="true"></div>
    <div class="cert-marquee__viewport">
      <div class="cert-marquee__track">
        <?php foreach ([false, true] as $dup): // second pass is the seamless-loop copy, hidden from assistive tech ?>
        <?php foreach ($cert_groups as $i => $group): ?>
        <?php $tip_id = 'cert-tip-' . $i; ?>
        <div class="cert-logo-wrap<?= $dup ? ' cert-logo-wrap--dup' : '' ?>"<?= $dup ? ' aria-hidden="true"' : '' ?>>
          <button
            type="button"
            class="cert-logo"
            <?= $dup ? 'tabindex="-1"' : 'aria-describedby="' . $tip_id . '"' ?>
          >
            <?php if (isset($group['logo'])): ?>
            <img
              src="/assets/img/Certifications%20logos/<?= rawurlencode($group['logo']) ?>"
              alt="<?= $e($group['name']) ?>"
              width="<?= $group['width'] ?>"
              height="<?= $group['height'] ?>"
              class="cert-logo__img"
              style="--logo-scale: <?= $group['scale'] ?>"
              loading="lazy"
              decoding="async"
            >
            <?php else: ?>
            <span class="cert-logo__wordmark"><?= $e($group['name']) ?></span>
            <?php endif; ?>
          </button>
          <div class="cert-logo__popover"<?= $dup ? '' : ' id="' . $tip_id . '"' ?> role="tooltip">
            <span class="cert-logo__popover-title"><?= $e($group['company'] ?? $group['name']) ?></span>
            <ul class="cert-logo__specs">
              <?php foreach ($group['certs'] as $cert): ?>
              <?php
                $label = isset($cert['label_key']) ? __r('about.certifications.' . $cert['label_key']) : $cert['label'];
                if (isset($group['company'])) {
                    $detail = isset($cert['issuer']) ? __r('about.certifications.issued_by_only', ['issuer' => $cert['issuer']]) : '';
                } else {
                    $detail = isset($cert['issuer'])
                        ? __r('about.certifications.issued_by', ['company' => $cert['company'], 'issuer' => $cert['issuer']])
                        : $cert['company'];
                }
              ?>
              <li class="cert-logo__spec">
                <span class="cert-logo__spec-label"><?= $e($label) ?></span>
                <?php if ($detail !== ''): ?>
                <span class="cert-logo__spec-detail"><?= $e($detail) ?></span>
                <?php endif; ?>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <?php endforeach; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
