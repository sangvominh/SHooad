<?php
// Product data is passed from controller
if (!isset($product)) {
    header('Location: /SHooad/public/user');
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
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Pursuit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/SHooad/public/assets/css/custom.css">
</head>
</head>
<body class="bg-white" data-stock="<?php echo htmlspecialchars($product['stock'] ?? 0); ?>" data-product-id="<?php echo $productId; ?>">
    <!-- Header & Navigation -->
    <?php include 'partials/header.php'; ?>
    
    <!-- Product Detail Container -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Gallery -->
            <?php include 'partials/product-gallery.php'; ?>
            
            <!-- Product Info -->
            <?php include 'partials/product-info.php'; ?>
        </div>
        
        <!-- Product Description -->
        <?php include 'partials/product-description.php'; ?>
    </div>
    
    <!-- Product Rating -->
    <?php include 'partials/product-rating.php'; ?>
    
    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="/SHooad/public/assets/js/user/dropdown.js"></script>
    <script src="/SHooad/public/assets/js/user/product-gallery.js"></script>
</body>
</html>
