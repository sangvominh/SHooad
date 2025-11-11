<!-- About This Product Section -->
<div class="mt-16 border-t pt-12">
    <h2 class="text-2xl font-bold mb-6">About this product</h2>
    
    <!-- Main Description -->
    <p class="text-gray-600 leading-relaxed mb-6">
        <?php echo htmlspecialchars($product['description']); ?>
    </p>
    
    <!-- Features List -->
    <ul class="space-y-3 mb-6">
        <?php foreach ($product['features'] as $feature): ?>
        <li class="flex items-start gap-3 text-gray-600">
            <span class="text-teal-600 font-bold mt-1">•</span>
            <span><?php echo htmlspecialchars($feature); ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
    
    <!-- Note Section -->
    <div class="bg-gray-50 p-4 rounded">
        <p class="text-gray-600">
            <span class="font-semibold">Note:</span> <?php echo htmlspecialchars($product['note']); ?>
        </p>
    </div>
</div>
