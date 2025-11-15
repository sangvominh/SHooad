<?php
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
LanguageHelper::init();
?>

<!-- Shop Information Section -->
<div class="mt-8 mb-8">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-6">
            <div class="flex items-start gap-6">
                <!-- Shop Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-store text-white text-4xl"></i>
                    </div>
                </div>
                
                <!-- Shop Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($product['shop_name'] ?? 'Unknown Shop'); ?></h2>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> <?php echo LanguageHelper::t('shop.verified'); ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($product['shop_description'])): ?>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars($product['shop_description']); ?></p>
                    <?php endif; ?>
                    
                    <!-- Shop Stats -->
                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-box text-blue-600"></i>
                            <span class="text-gray-700"><span class="font-semibold"><?php echo number_format($product['shop_products_count'] ?? 0); ?></span> <?php echo LanguageHelper::t('shop.products'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="text-gray-700"><span class="font-semibold"><?php echo number_format($product['shop_rating'] ?? 0, 1); ?></span> <?php echo LanguageHelper::t('shop.rating'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-users text-green-600"></i>
                            <span class="text-gray-700"><span class="font-semibold"><?php echo number_format($product['shop_followers'] ?? 0); ?></span> <?php echo LanguageHelper::t('shop.followers'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                    <a href="/SHooad/public/customer/shop-detail?shop_id=<?php echo $product['shop_id'] ?? ''; ?>" 
                       class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition text-center flex items-center gap-2">
                        <i class="fas fa-store"></i>
                        <?php echo LanguageHelper::t('shop.visit_shop'); ?>
                    </a>
                    <button class="px-6 py-2.5 border-2 border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition flex items-center gap-2 justify-center">
                        <i class="fas fa-heart"></i>
                        <?php echo LanguageHelper::t('shop.follow'); ?>
                    </button>
                </div>
            </div>
        </div>
</div>
