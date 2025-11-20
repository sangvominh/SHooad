<?php
// Product data is passed from controller
$product = $data['product'] ?? null;

if (!$product) {
    header('Location: /SHooad/public/customer');
    exit();
}

// Add defaults for missing fields (only if not already set)
if (!isset($product['rating'])) $product['rating'] = 0;
if (!isset($product['reviews_count'])) $product['reviews_count'] = 0;
if (!isset($product['reviews'])) $product['reviews'] = [];
if (!isset($product['features'])) $product['features'] = [];
if (!isset($product['note'])) $product['note'] = '';

// Set first color as active
if (!empty($product['colors'])) {
    $product['colors'][0]['active'] = true;
}

$productId = $product['id'] ?? 0;
?>

<!-- Product Detail Container -->
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8 lg:py-12 max-w-7xl" data-stock="<?php echo htmlspecialchars($product['stock'] ?? 0); ?>" data-product-id="<?php echo $productId; ?>">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
        <!-- Product Gallery -->
        <?php include __DIR__ . '/components/product-gallery.php'; ?>
        
        <!-- Product Info -->
        <?php include __DIR__ . '/components/product-info.php'; ?>
    </div>
    
    <!-- Product Description -->
    <?php include __DIR__ . '/components/product-description.php'; ?>
    
    <!-- Shop Information -->
    <?php include __DIR__ . '/components/shop-info.php'; ?>
    
    <!-- Product Rating -->
    <?php include __DIR__ . '/components/product-rating.php'; ?>
</div>

<script src="/SHooad/public/assets/js/customer/product-gallery.js"></script>
