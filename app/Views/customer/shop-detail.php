<?php
require_once __DIR__ . '/../../Helpers/LanguageHelper.php';
LanguageHelper::init();

// Shop data is passed from controller
$shop = $data['shop'] ?? null;
$products = $data['products'] ?? [];

if (!$shop) {
    header('Location: /SHooad/public/customer');
    exit();
}
?>

<!-- Shop Detail Page -->
<div class="min-h-screen bg-gray-50">
    <!-- Shop Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-start gap-8">
                    <!-- Shop Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-32 h-32 bg-white rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-store text-blue-600 text-5xl"></i>
                        </div>
                    </div>
                    
                    <!-- Shop Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h1 class="text-4xl font-bold"><?php echo htmlspecialchars($shop['name'] ?? 'Unknown Shop'); ?></h1>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> <?php echo LanguageHelper::t('shop.verified'); ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($shop['description'])): ?>
                        <p class="text-blue-100 text-lg mb-6 max-w-3xl"><?php echo htmlspecialchars($shop['description']); ?></p>
                        <?php endif; ?>
                        
                        <!-- Shop Stats -->
                        <div class="grid grid-cols-4 gap-6 bg-white/10 backdrop-blur-sm rounded-xl p-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1"><?php echo number_format($shop['products_count'] ?? 0); ?></div>
                                <div class="text-blue-100 text-sm"><?php echo LanguageHelper::t('shop.products'); ?></div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <span class="text-3xl font-bold"><?php echo number_format($shop['rating'] ?? 0, 1); ?></span>
                                    <i class="fas fa-star text-yellow-300 text-2xl"></i>
                                </div>
                                <div class="text-blue-100 text-sm"><?php echo LanguageHelper::t('shop.rating'); ?></div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1"><?php echo number_format($shop['followers'] ?? 0); ?></div>
                                <div class="text-blue-100 text-sm"><?php echo LanguageHelper::t('shop.followers'); ?></div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1"><?php echo number_format($shop['orders_count'] ?? 0); ?></div>
                                <div class="text-blue-100 text-sm"><?php echo LanguageHelper::t('shop.orders_completed'); ?></div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 mt-6">
                            <button class="px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition flex items-center gap-2 shadow-lg">
                                <i class="fas fa-heart"></i>
                                <?php echo LanguageHelper::t('shop.follow'); ?>
                            </button>
                            <button class="px-8 py-3 bg-white/20 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-white/30 transition flex items-center gap-2">
                                <i class="fas fa-comment"></i>
                                <?php echo LanguageHelper::t('shop.chat'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Shop Information Tabs -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex gap-8 px-6">
                        <button class="py-4 px-2 border-b-2 border-blue-600 text-blue-600 font-semibold">
                            <?php echo LanguageHelper::t('shop.all_products'); ?>
                        </button>
                        <button class="py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            <?php echo LanguageHelper::t('shop.about'); ?>
                        </button>
                    </nav>
                </div>
                
                <!-- Products Grid -->
                <div class="p-6">
                    <?php if (empty($products)): ?>
                    <div class="text-center py-16">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg"><?php echo LanguageHelper::t('shop.no_products'); ?></p>
                    </div>
                    <?php else: ?>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        <?php foreach ($products as $product): ?>
                        <a href="/SHooad/public/customer/product-detail?id=<?php echo $product['id']; ?>" 
                           class="group bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
                            <!-- Product Image -->
                            <div class="aspect-square overflow-hidden bg-gray-100">
                                <?php 
                                $firstImage = !empty($product['image']) ? $product['image'] : '/SHooad/public/assets/products/default.jpg';
                                ?>
                                <img src="<?php echo htmlspecialchars($firstImage); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            </div>
                            
                            <!-- Product Info -->
                            <div class="p-3">
                                <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h3>
                                
                                <!-- Price -->
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-red-600 font-bold">
                                        <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
                                    </span>
                                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                    <span class="text-xs text-gray-400 line-through">
                                        <?php echo number_format($product['original_price'], 0, ',', '.'); ?>₫
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Rating & Sold -->
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span><?php echo number_format($product['rating'] ?? 0, 1); ?></span>
                                    </div>
                                    <span><?php echo LanguageHelper::t('product.sold'); ?>: <?php echo number_format($product['sold'] ?? 0); ?></span>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
