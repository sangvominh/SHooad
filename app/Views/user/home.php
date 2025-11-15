<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
/>
<?php
// Data is passed from controller
$banners = $data['banners'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/SHooad/public/assets/css/custom.css">
</head>
<body class="bg-white">
    <!-- Header & Navigation -->
    <?php include 'partials/header.php'; ?>
    
    <!-- Banner Section -->
    <?php include 'partials/banner.php'; ?>

    <!-- Sale Advertisement -->
    <img src="/SHooad/public/assets/sale/sale_advertisement.jpg" alt="sale-image" class="w-80% my-16 mx-auto block rounded-xl shadow-lg shadow-gray-400">
    
    <!-- Categories Section -->
    <?php include 'partials/categories-section.php'; ?>

    <!-- Popular Products Section -->
    <?php include 'partials/products-section.php'; ?>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="/SHooad/public/assets/js/user/dropdown.js"></script>
    <script src="/SHooad/public/assets/js/user/product-hover.js"></script>


</body>
</html>

