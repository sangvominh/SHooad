<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items Section -->
        <div class="lg:col-span-2">
            <?php include __DIR__ . '/partials/cart-items.php'; ?>
        </div>

        <!-- Order Summary Section -->
        <div class="lg:col-span-1">
            <?php include __DIR__ . '/partials/order-summary.php'; ?>
        </div>
    </div>
</main>

<!-- Cart Scripts -->
<script src="/SHooad/public/assets/js/customer/cart.js"></script>