<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

<body class="bg-gray-50">
    <!-- Header -->
    <?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    include 'partials/header.php';
    ?>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items Section -->
            <div class="lg:col-span-2">
                <?php include 'partials/cart-items.php'; ?>
            </div>

            <!-- Order Summary Section -->
            <div class="lg:col-span-1">
                <?php include 'partials/order-summary.php'; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!-- Cart Scripts -->
    <script src="/SHooad/public/assets/js/customer/cart.js"></script>
</body>

</html>