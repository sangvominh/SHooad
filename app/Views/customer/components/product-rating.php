<!-- Product Rating Section -->
<div class="container mx-auto px-4 py-12 border-t border-gray-200">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Đánh giá sản phẩm</h2>
        
        <!-- Rating Summary -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="flex items-center gap-8">
                <!-- Overall Rating -->
                <div class="text-center">
                    <div class="text-5xl font-bold text-gray-900 mb-2">
                        <?php echo number_format($product['rating'] ?? 0, 1); ?>
                    </div>
                    <div class="flex gap-1 justify-center mb-2">
                        <?php
                        $rating = floatval($product['rating'] ?? 0);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= floor($rating)) {
                                echo '<span class="text-yellow-400 text-xl">★</span>';
                            } else {
                                echo '<span class="text-gray-300 text-xl">★</span>';
                            }
                        }
                        ?>
                    </div>
                    <div class="text-gray-600 text-sm">
                        <?php echo $product['reviews_count'] ?? 0; ?> đánh giá
                    </div>
                </div>
                
                <!-- Rating Breakdown -->
                <div class="flex-1">
                    <?php
                    $reviews = $product['reviews'] ?? [];
                    $ratingCounts = array_fill(1, 5, 0);
                    foreach ($reviews as $review) {
                        $ratingCounts[$review['rating']]++;
                    }
                    $totalReviews = count($reviews);
                    
                    for ($star = 5; $star >= 1; $star--):
                        $count = $ratingCounts[$star];
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    ?>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-sm font-medium w-12"><?php echo $star; ?> sao</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-12 text-right"><?php echo $count; ?></span>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        
        <!-- Individual Reviews -->
        <?php if (!empty($reviews)): ?>
        <div class="space-y-6">
            <?php foreach ($reviews as $review): ?>
            <div class="border-b border-gray-200 pb-6">
                <div class="flex items-start gap-4">
                    <!-- Avatar -->
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        <?php echo strtoupper(substr($review['customer_name'], 0, 1)); ?>
                    </div>
                    
                    <!-- Review Content -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($review['customer_name']); ?></h4>
                            <div class="flex gap-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="<?php echo $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($review['comment']); ?></p>
                        
                        <div class="text-xs text-gray-500">
                            <?php 
                            $reviewDate = new DateTime($review['created_at']);
                            echo $reviewDate->format('F j, Y');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <p class="text-gray-500 text-lg">No reviews yet. Be the first to review this product!</p>
        </div>
        <?php endif; ?>
    </div>
</div>
