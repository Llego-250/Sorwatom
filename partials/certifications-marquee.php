<?php
$cert_items = require __DIR__ . '/certifications-data.php';
$cert_loop  = array_merge($cert_items, $cert_items);
?>
<section class="section certifications-section" aria-labelledby="certifications-heading">
  <div class="container certifications-section__header">
    <h2 id="certifications-heading" class="eyebrow"><?= __t('about.certifications.eyebrow') ?></h2>
  </div>
  <div class="cert-marquee" role="region" aria-label="<?= __t('about.certifications.eyebrow') ?>">
    <div class="cert-marquee__track">
      <?php foreach ($cert_loop as $cert): ?>
      <?php
        $label = $cert['label'];
        $detail = $cert['detail'];
      ?>
      <button
        type="button"
        class="cert-pill"
        title="<?= htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') ?>"
        aria-label="<?= htmlspecialchars($label . '. ' . $detail, ENT_QUOTES, 'UTF-8') ?>"
      >
        <span class="cert-pill__label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
        <span class="cert-pill__tip"><?= htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') ?></span>
      </button>
      <?php endforeach; ?>
    </div>
  </div>
</section>
