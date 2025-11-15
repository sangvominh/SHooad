<?php
$has_sale = isset($item['original_price']) && $item['original_price'] != $item['price'];
?>

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
                <span><strong>Size:</strong> <?php echo htmlspecialchars($item['size']); ?></span>
                <span><strong>Color:</strong> <?php echo htmlspecialchars($item['color']); ?></span>
            </div>

            <!-- Price Section -->
            <div class="flex gap-3 mt-3 items-center">
                <?php if ($has_sale): ?>
                    <span class="text-gray-400 line-through">$<?php echo number_format($item['original_price'], 2); ?></span>
                    <span class="text-xl font-bold text-red-500">$<?php echo number_format($item['price'], 2); ?></span>
                <?php else: ?>
                    <span class="text-xl font-bold text-gray-900">$<?php echo number_format($item['price'], 2); ?></span>
                <?php endif; ?>
            </div>

            <!-- Quantity Controls and Edit Button -->
            <div class="flex gap-4 mt-4 items-center">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-600">Quantity:</span>
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

                    <button class="px-4 py-1 text-red-600 border border-red-600 rounded hover:bg-red-50 transition text-sm font-medium remove-btn" data-item-id="<?php echo $item['cart_item_id']; ?>">Remove</button>
                </div>
            </div>
        </div>
    </div>
</div>