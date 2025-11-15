<?php
// Product data is passed from controller
$product = $data['product'] ?? null;

if (!$product) {
    header('Location: /SHooad/public/customer');
    exit();
}

// Add defaults for missing fields
$product['rating'] = 0;
$product['reviews_count'] = 0;
$product['features'] = [];
$product['note'] = '';

// Set first color as active
if (!empty($product['colors'])) {
    $product['colors'][0]['active'] = true;
}

$productId = $product['id'] ?? 0;
?>

<!-- Product Detail Container -->
<div class="container mx-auto px-4 py-12" data-stock="<?php echo htmlspecialchars($product['stock'] ?? 0); ?>" data-product-id="<?php echo $productId; ?>">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Gallery -->
        <?php include __DIR__ . '/components/product-gallery.php'; ?>
        
        <!-- Product Info -->
        <?php include __DIR__ . '/components/product-info.php'; ?>
    </div>
    
    <!-- Product Description -->
    <?php include __DIR__ . '/components/product-description.php'; ?>
</div>

<!-- Product Rating -->
<?php include __DIR__ . '/components/product-rating.php'; ?>

<!-- Scripts -->
<script src="/SHooad/public/assets/js/customer/dropdown.js"></script>
<script src="/SHooad/public/assets/js/customer/product-gallery.js"></script>
<link rel="stylesheet" href="/SHooad/public/assets/css/custom.css">
