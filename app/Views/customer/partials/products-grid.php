<?php
// $products should be provided by the caller (products.php)
if (!isset($products)) $products = [];
?>

<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($products as $product): ?>
        <?php include __DIR__ . '/../components/product-card.php'; ?>
    <?php endforeach; ?>
</div>