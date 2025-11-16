<div class="block group relative product-card transition-all duration-200 overflow-hidden">
    <!-- Product Image Container -->
    <a href="/SHooad/public/customer/product-detail?id=<?php echo urlencode($product['id']); ?>" class="block relative bg-gray-100 mb-4 overflow-hidden">
        <?php $hasHover = isset($product['image_hover']) && !empty($product['image_hover']); ?>
        <img
            src="<?php echo htmlspecialchars($product['image'] ?? '/SHooad/public/assets/logo/default-avatar.png'); ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
            class="w-full h-56 object-cover transition-opacity duration-300 <?php echo $hasHover ? 'group-hover:opacity-0' : ''; ?>">
        <?php if ($hasHover): ?>
        <img
            src="<?php echo htmlspecialchars($product['image_hover']); ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
            class="w-full h-56 object-cover absolute inset-0 transition-opacity duration-300 opacity-0 group-hover:opacity-100">
        <?php endif; ?>
        
        <!-- Add to Cart Icon - Floating on Hover -->
        <button class="add-to-cart-btn absolute bottom-4 right-4 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-900 hover:bg-[#001F5D] hover:text-white transition-all duration-300 transform translate-y-16 opacity-0 group-hover:translate-y-0 group-hover:opacity-100" data-product-id="<?php echo $product['id']; ?>" onclick="event.preventDefault();">
            <i class="fa-solid fa-cart-plus text-xl"></i>
        </button>
    </a>

    <!-- Product Info -->
    <a href="/SHooad/public/customer/product-detail?id=<?php echo urlencode($product['id']); ?>" class="block space-y-2 p-3">
        <!-- Product Name -->
        <h3 class="text-base font-semibold text-gray-900 hover:text-[#001F5D] transition-colors line-clamp-2">
            <?php echo htmlspecialchars($product['name']); ?>
        </h3>

        <!-- Brand -->
        <?php if (isset($product['brand']) && !empty($product['brand'])): ?>
        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($product['brand']); ?></p>
        <?php endif; ?>

        <!-- Price -->
        <div class="text-lg font-bold text-[#001F5D]">
            <?php echo number_format(isset($product['price']) ? $product['price'] : 0, 0, ',', '.'); ?>₫
        </div>
    </a>
</div>