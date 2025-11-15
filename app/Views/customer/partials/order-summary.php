<?php
// Load language helper
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';

$all_items = [
    [
        'id' => 1,
        'name' => 'Modern Green Sweater',
        'price' => 60,
        'original_price' => 120,
        'quantity' => 1
    ],
    [
        'id' => 2,
        'name' => 'Corporate Office Shoes',
        'price' => 399,
        'original_price' => 399,
        'quantity' => 1
    ],
    [
        'id' => 3,
        'name' => 'Women Hand Bags',
        'price' => 123,
        'original_price' => 150,
        'quantity' => 2
    ]
];

$selected_count = 0;
$original_total = 0;
$sale_total = 0;

foreach ($all_items as $item) {
    $item_qty = $item['quantity'];
    $original_total += $item['original_price'] * $item_qty;
    $sale_total += $item['price'] * $item_qty;
    $selected_count++;
}

$savings = $original_total - $sale_total;
$shipping = 0;
$grand_total = $sale_total + $shipping;
?>

<div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-8 h-fit">
    <h2 class="text-2xl font-bold text-gray-900 mb-6"><?= LanguageHelper::t('checkout.order_summary') ?> (<span id="selected-count">0</span>)</h2>
    
    <div class="space-y-4 border-b border-gray-200 pb-6">
        <!-- Original Price -->
        <div class="flex justify-between text-gray-700">
            <span><?= LanguageHelper::t('cart.price') ?></span>
            <span id="original-price-display">0₫</span>
        </div>

        <!-- Savings -->
        <div class="flex justify-between text-gray-700">
            <span><?= LanguageHelper::t('cart.subtotal') ?></span>
            <span id="savings-display" class="text-green-600">0₫</span>
        </div>

        <!-- Sale Price -->
        <div class="flex justify-between text-gray-700">
            <span><?= LanguageHelper::t('checkout.subtotal') ?></span>
            <span id="sale-price-display">0₫</span>
        </div>

        <!-- Shipping -->
        <div class="flex justify-between text-gray-700">
            <span><?= LanguageHelper::t('cart.shipping') ?></span>
            <span id="shipping-display" class="text-green-600"><?= LanguageHelper::t('cart.shipping') ?></span>
        </div>
    </div>

    <!-- Total -->
    <div class="flex justify-between items-center py-6 border-b border-gray-200">
        <span class="text-xl font-bold text-gray-900"><?= LanguageHelper::t('cart.grand_total') ?></span>
        <span class="text-3xl font-bold text-red-600" id="total-display">0₫</span>
    </div>

    <!-- Payment Button -->
    <button id="checkoutBtn" disabled
        class="w-full bg-gray-400 text-gray-700 font-bold py-3 rounded-lg mt-6 cursor-not-allowed transition">
        <?= LanguageHelper::t('cart.proceed_checkout') ?>
    </button>
    
</div>

<script>
// Update checkout button state
window.updateCheckoutButton = function() {
    var selectedCount = parseInt(document.getElementById('selected-count').textContent) || 0;
    var checkoutBtn = document.getElementById('checkoutBtn');
    
    if (!checkoutBtn) return;
    
    if (selectedCount > 0) {
        checkoutBtn.disabled = false;
        checkoutBtn.classList.remove('bg-gray-400', 'text-gray-700', 'cursor-not-allowed');
        checkoutBtn.classList.add('bg-yellow-400', 'text-gray-900', 'hover:bg-yellow-500');
        checkoutBtn.onclick = function() {
            window.location.href = '/SHooad/public/customer/checkout';
        };
    } else {
        checkoutBtn.disabled = true;
        checkoutBtn.classList.add('bg-gray-400', 'text-gray-700', 'cursor-not-allowed');
        checkoutBtn.classList.remove('bg-yellow-400', 'text-gray-900', 'hover:bg-yellow-500');
        checkoutBtn.onclick = null;
    }
};

// Initial call
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.updateCheckoutButton === 'function') {
        window.updateCheckoutButton();
    }
});
</script>
