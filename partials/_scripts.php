<!-- Core site scripts — always loaded. site.js (nav, popup, footer) runs first. -->
<script src="/assets/js/site.js" defer></script>
<script src="/assets/js/main.js" defer></script>

<!-- Per-page scripts -->
<?php foreach ($page_js ?? [] as $js): ?>
<script src="/assets/js/<?= htmlspecialchars($js) ?>" defer></script>
<?php endforeach; ?>
