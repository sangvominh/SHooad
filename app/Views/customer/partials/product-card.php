<a href="/SHooad/public/customer/product-detail?id=<?php echo urlencode($product['id']); ?>" class="block group relative product-card  transition-all duration-200 transform hover:scale-105 overflow-hidden">
    <!-- Product Image Container -->
    <div class="relative bg-gray-100 mb-4">
        <img
            src="<?php echo htmlspecialchars(isset($product['thumbnail_url']) ? $product['thumbnail_url'] : (isset($product['image']) ? $product['image'] : '/SHooad/public/assets/logo/default-avatar.png')) ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
            class="w-full h-96 object-cover transition-transform duration-200">
        <!-- Removed yellow overlay; hover now uses card border + shadow/scale -->
    </div>

    <!-- Product Info -->
    <div class="space-y-3">
        <!-- Category & Wishlist -->
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 font-medium"><?php echo htmlspecialchars(isset($product['category']) ? $product['category'] : ''); ?></span>
            <!-- <button class="transition-colors <?php echo $product['wishlisted'] ? 'text-red-500' : 'text-gray-400 hover:text-red-500'; ?>">
                ♥
            </button> -->
        </div>

        <!-- Product Name -->
        <h3 class="text-lg font-bold text-black"><?php echo htmlspecialchars($product['name']); ?></h3>

        <!-- Rating & Price -->
        <div class="flex items-center gap-2">
            <span class="text-yellow-400">★</span>
            <span class="text-sm font-semibold text-gray-800"><?php echo isset($product['rating']) ? $product['rating'] : '0.0'; ?> (<?php echo isset($product['reviews_count']) ? $product['reviews_count'] : 0; ?>)</span>
            <div class="ml-auto text-right">
                <?php if (isset($product['original_price']) && floatval($product['original_price']) > floatval($product['price'])): ?>
                    <?php $orig = floatval($product['original_price']);
                    $price = floatval($product['price']);
                    $pct = $orig > 0 ? round((($orig - $price) / $orig) * 100) : 0; ?>
                    <div class="text-gray-400 line-through text-sm">$<?php echo number_format($orig, 2); ?></div>
                    <div class="text-xl font-bold text-red-500">$<?php echo number_format($price, 2); ?> <?php if ($pct > 0): ?><span class="text-sm text-red-600">(-<?php echo $pct; ?>%)</span><?php endif; ?></div>
                <?php else: ?>
                    <div class="text-xl font-bold text-black">$<?php echo number_format(isset($product['price']) ? $product['price'] : 0, 2); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add to Cart Button -->
        <button class="w-full py-3 border-2 border-gray-800 text-gray-800 font-semibold hover:bg-teal-700 hover:text-white hover:border-teal-700 transition-colors first:bg-teal-700 first:text-white first:border-teal-700 add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
            Add to Cart
        </button>
    </div>
</a>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                var productId = this.getAttribute('data-product-id');
                // Hiệu ứng lắc
                if (window.shakeCartIcon) shakeCartIcon();
                // Gọi API thêm vào giỏ hàng
                try {
                    const fd = new FormData();
                    fd.append('product_id', productId);
                    fd.append('quantity', 1);
                    const res = await fetch('/SHooad/app/Routes/update-cart.php', {
                        method: 'POST',
                        body: fd
                    });
                    const json = await res.json();
                    if (json && json.cart_total !== undefined && window.updateCartBadge) {
                        updateCartBadge(json.cart_total);
                    }
                } catch (err) {}
            });
        });
    });
</script>