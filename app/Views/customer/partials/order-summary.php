<?php
// Load language helper
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
?>

<div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-8 h-fit">
    <h2 class="text-2xl font-bold text-gray-900 mb-6"><?= LanguageHelper::t('checkout.order_summary') ?> (<span id="selected-count">0</span>)</h2>
    
    <div class="space-y-4 border-b border-gray-200 pb-6">
        <!-- Original Total -->
        <div class="flex justify-between text-gray-700">
            <span><?= LanguageHelper::t('cart.original_total') ?></span>
            <span id="original-price-display">0₫</span>
        </div>

        <!-- Discount -->
        <div class="flex justify-between text-gray-700">
            <span><?= LanguageHelper::t('cart.discount') ?></span>
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
            <span id="shipping-display" class="text-gray-900">0₫</span>
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
