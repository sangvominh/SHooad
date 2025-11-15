<?php
if (session_status() == PHP_SESSION_NONE) session_start();
// Lấy dữ liệu từ controller/service
$cart_items = $data['cart_items'] ?? [];

// Load language helper
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
?>

<style>
/* Hide number input spinners for quantity inputs on this page */
input.no-spinner::-webkit-outer-spin-button,
input.no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
input.no-spinner { -moz-appearance: textfield; }
</style>

<div class="space-y-4">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-900"><?= LanguageHelper::t('cart.title') ?> (<span id="cart-count"><?php echo count($cart_items); ?></span> <?= LanguageHelper::t('checkout.items') ?>)</h2>
        <a href="/SHooad/public/customer/products" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <?= LanguageHelper::t('cart.continue_shopping') ?>
        </a>
    </div>
    
    <div class="space-y-4">
        <?php foreach ($cart_items as $index => $item): 
            $has_sale = isset($item['original_price']) && $item['original_price'] != $item['price'];
        ?>
        
        <!-- Cart Item -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
            <div class="flex gap-4">
                <!-- Checkbox -->
                <div class="flex items-start pt-2">
                    <input
                        type="checkbox"
                        class="w-5 h-5 text-teal-600 rounded cursor-pointer cart-select"
                        data-item-id="<?php echo $item['cart_item_id']; ?>"
                        data-price="<?php echo $item['price']; ?>"
                        data-original-price="<?php echo $item['original_price']; ?>"
                        data-quantity="<?php echo $item['quantity']; ?>"
                        <?php echo (isset($item['selected']) && $item['selected']) ? 'checked' : ''; ?>>
                </div>

                <!-- Product Image -->
                <div class="flex-shrink-0">
                    <?php
                    $img = '';
                    if (!empty($item['image'])) {
                        $img = $item['image'];
                    } elseif (!empty($item['thumbnail_url'])) {
                        $thumb = $item['thumbnail_url'];
                        if (!preg_match('#^(https?://|/)#i', $thumb)) {
                            $thumb = '/SHooad/public/' . ltrim($thumb, '/');
                        }
                        $img = $thumb;
                    } else {
                        $img = '/SHooad/public/assets/logo/default-avatar.png';
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-32 h-32 object-cover rounded">
                </div>

                <!-- Product Details -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($item['name']); ?></h3>

                    <!-- Size and Color -->
                    <div class="flex gap-6 mt-2 text-sm text-gray-600">
                        <span><strong><?= LanguageHelper::t('cart.size') ?>:</strong> <?php echo htmlspecialchars($item['size']); ?></span>
                        <span><strong><?= LanguageHelper::t('cart.color') ?>:</strong> <?php echo htmlspecialchars($item['color']); ?></span>
                    </div>

                    <!-- Price Section -->
                    <div class="flex gap-3 mt-3 items-center">
                        <?php if ($has_sale): ?>
                            <span class="text-gray-400 line-through"><?php echo number_format($item['original_price'], 0, ',', '.'); ?>₫</span>
                            <span class="text-xl font-bold text-red-500"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</span>
                        <?php else: ?>
                            <span class="text-xl font-bold text-gray-900"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</span>
                        <?php endif; ?>
                    </div>

                    <!-- Quantity Controls and Edit Button -->
                    <div class="flex gap-4 mt-4 items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-600"><?= LanguageHelper::t('cart.quantity') ?>:</span>
                            <button class="w-8 h-8 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition qty-minus" data-item-id="<?php echo $item['cart_item_id']; ?>">−</button>
                            <input
                                type="number"
                                value="<?php echo $item['quantity']; ?>"
                                class="w-12 text-center border border-gray-300 rounded qty-input no-spinner"
                                min="1"
                                max="<?php echo isset($item['stock']) ? intval($item['stock']) : 9999; ?>"
                                data-item-id="<?php echo $item['cart_item_id']; ?>"
                                data-stock="<?php echo isset($item['stock']) ? intval($item['stock']) : 0; ?>">
                            <button class="w-8 h-8 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition qty-plus" data-item-id="<?php echo $item['cart_item_id']; ?>">+</button>
                        </div>

                        <!-- Edit and Remove Buttons -->
                        <div class="flex gap-2 ml-auto">
                            <button class="px-4 py-1 text-red-600 border border-red-600 rounded hover:bg-red-50 transition text-sm font-medium remove-btn" data-item-id="<?php echo $item['cart_item_id']; ?>"><?= LanguageHelper::t('cart.remove') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endforeach; ?>
    </div>
</div>
