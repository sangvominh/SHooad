<?php
// $products should be provided by the caller (products.php)
if (!isset($products)) $products = [];
?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($products as $product): ?>
        <?php include 'partials/product-card.php'; ?>
    <?php endforeach; ?>
</div>