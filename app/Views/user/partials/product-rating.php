<!-- Product Rating Section -->
<div class="container mx-auto px-4 py-12 border-t border-gray-200">
    <div class="max-w-2xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Product Reviews</h2>
        
        <!-- Rating Display -->
        <div class="flex items-center gap-6">
            <!-- Star Rating -->
            <div class="flex gap-2">
                <?php
                $rating = floatval($product['rating'] ?? 0);
                $fullStars = floor($rating);
                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $fullStars) {
                        // Full yellow star
                        echo '<span class="text-3xl text-yellow-400">★</span>';
                    } elseif ($i == ($fullStars + 1) && $hasHalfStar) {
                        // Half yellow star
                        echo '<span class="text-3xl relative inline-block">
                                <span class="text-gray-300">★</span>
                                <span class="absolute left-0 top-0 text-yellow-400 overflow-hidden" style="width: 50%;">★</span>
                              </span>';
                    } else {
                        // Gray star
                        echo '<span class="text-3xl text-gray-300">★</span>';
                    }
                }
                ?>
            </div>
            
            <!-- Rating Stats -->
            <div>
                <div class="text-2xl font-bold text-gray-900">
                    <?php echo number_format($rating, 1); ?>
                </div>
                <div class="text-gray-600">
                    <?php echo $product['reviews_count'] ?? 0; ?> reviews
                </div>
            </div>
        </div>
    </div>
</div>
