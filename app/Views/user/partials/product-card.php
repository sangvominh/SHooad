<a href="#" class="block group relative product-card  transition-all duration-200 transform hover:scale-105 overflow-hidden">
    <!-- Product Image Container -->
    <div class="relative bg-gray-100 mb-4">
        <img 
            src="<?php echo htmlspecialchars($product['image'])?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
            class="w-full h-96 object-cover transition-transform duration-200"
        >
        <!-- Removed yellow overlay; hover now uses card border + shadow/scale --> 
    </div>
    
    <!-- Product Info -->
    <div class="space-y-3">
        <!-- Category & Wishlist -->
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 font-medium"><?php echo htmlspecialchars($product['category']); ?></span>
            <button class="transition-colors <?php echo $product['wishlisted'] ? 'text-red-500' : 'text-gray-400 hover:text-red-500'; ?>">
                ♥
            </button>
        </div>
        
        <!-- Product Name -->
        <h3 class="text-lg font-bold text-black"><?php echo htmlspecialchars($product['name']); ?></h3>
        
        <!-- Rating & Price -->
        <div class="flex items-center gap-2">
            <span class="text-yellow-400">★</span>
            <span class="text-sm font-semibold text-gray-800"><?php echo $product['rating']; ?> (<?php echo $product['reviews']; ?>)</span>
            <span class="text-lg font-bold text-black ml-auto">$<?php echo number_format($product['price']); ?></span>
        </div>
        
        <!-- Add to Cart Button -->
        <button class="w-full py-3 border-2 border-gray-800 text-gray-800 font-semibold hover:bg-teal-700 hover:text-white hover:border-teal-700 transition-colors first:bg-teal-700 first:text-white first:border-teal-700">
            Add to Cart
        </button>
    </div>
</a>
