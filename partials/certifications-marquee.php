<?php
$cert_items = require __DIR__ . '/certifications-data.php';
$cert_loop  = array_merge($cert_items, $cert_items);
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
        <?php foreach ($cert_loop as $i => $cert): ?>
        <?php
          $label  = $cert['label'];
          $detail = $cert['detail'];
          $tip_id = 'cert-tip-' . $i;
        ?>
        <div class="cert-pill-wrap">
          <button
            type="button"
            class="cert-pill"
            aria-describedby="<?= $tip_id ?>"
          >
            <span class="cert-pill__label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
          </button>
          <div class="cert-pill__popover" id="<?= $tip_id ?>" role="tooltip">
            <span class="cert-pill__popover-detail"><?= htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
