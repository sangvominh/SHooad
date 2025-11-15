<!-- About This Product Section -->
<div class="mt-12 border-t pt-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Product Description</h2>
    
    <!-- Main Description -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
            <?php echo htmlspecialchars($product['description']); ?>
        </p>
    </div>
    
    <!-- Product Specifications -->
    <?php if (!empty($product['brand']) || !empty($product['stock'])): ?>
    <div class="mt-6 bg-gray-50 rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Specifications</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php if (!empty($product['brand'])): ?>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Brand:</span>
                <span class="text-gray-900"><?php echo htmlspecialchars($product['brand']); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($product['stock'])): ?>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Availability:</span>
                <span class="text-green-600 font-medium"><?php echo $product['stock']; ?> in stock</span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($product['colors'])): ?>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Available Colors:</span>
                <span class="text-gray-900"><?php echo count($product['colors']); ?> options</span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($product['sizes'])): ?>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Available Sizes:</span>
                <span class="text-gray-900"><?php echo implode(', ', $product['sizes']); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
