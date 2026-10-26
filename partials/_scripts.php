<!-- Core site scripts — always loaded. site.js (nav, popup, footer) runs first. -->
<script src="<?= asset_url('/assets/js/site.js') ?>" defer></script>
<script src="<?= asset_url('/assets/js/main.js') ?>" defer></script>

<!-- Per-page scripts -->
<?php foreach ($page_js ?? [] as $js): ?>
<script src="<?= htmlspecialchars(asset_url('/assets/js/' . $js)) ?>" defer></script>
<?php endforeach; ?>
