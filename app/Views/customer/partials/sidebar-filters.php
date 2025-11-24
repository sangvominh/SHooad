<?php
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
LanguageHelper::init();

// Lấy dữ liệu từ controller/service
$filters = $data['filters'] ?? ['categories' => [], 'brands' => [], 'sizes' => [], 'colors' => [], 'ratings' => []];
$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
$selectedBrand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$priceFrom = isset($_GET['price_from']) ? trim($_GET['price_from']) : '';
$priceTo = isset($_GET['price_to']) ? trim($_GET['price_to']) : '';
?>

<div class="space-y-4">
    <!-- Categories Section -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
        <h3 class="text-base font-bold text-gray-900 mb-3"><?php echo LanguageHelper::t('filters.categories'); ?></h3>
        <div class="space-y-1 max-h-96 overflow-y-auto">
            <!-- All Products Option -->
                <a href="/SHooad/public/customer/products" 
               class="block px-3 py-2 rounded text-sm transition <?php echo empty($selectedCategory) ? 'bg-teal-600 text-white font-semibold' : 'text-gray-700 hover:bg-teal-50'; ?>">
                <div class="flex items-center justify-between">
                    <span><?php echo LanguageHelper::t('filters.all_products'); ?></span>
                </div>
            </a>
            
            <?php foreach ($filters['categories'] as $category): ?>
                <?php 
                    $isSelected = ($selectedCategory === $category['name']);
                    $queryParams = $_GET;
                    $queryParams['category'] = $category['name'];
                    unset($queryParams['page']); // Reset page when changing category
                    $url = '/SHooad/public/customer/products?' . http_build_query($queryParams);
                ?>
                <a href="<?php echo htmlspecialchars($url); ?>" 
                   class="block px-3 py-2 rounded text-sm transition <?php echo $isSelected ? 'bg-teal-600 text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-teal-50'; ?>">
                    <div class="flex items-center justify-between">
                        <span><?php echo htmlspecialchars($category['name']); ?></span>
                        <span class="<?php echo $isSelected ? 'text-teal-100' : 'text-gray-400'; ?> text-xs">
                            (<?php echo $category['count']; ?>)
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Price Range Filter -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
        <h3 class="text-base font-bold text-gray-900 mb-3"><?php echo LanguageHelper::t('filters.price_range'); ?></h3>
        <form method="get" id="priceFilterForm">
            <!-- Preserve existing query params -->
            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key !== 'price_from' && $key !== 'price_to' && $key !== 'page'): ?>
                    <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="hidden" name="page" value="1">
            
            <div class="space-y-2">
                <input type="number" 
                       name="price_from" 
                       placeholder="<?php echo LanguageHelper::t('filters.min'); ?>" 
                       value="<?php echo htmlspecialchars($priceFrom); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <input type="number" 
                       name="price_to" 
                       placeholder="<?php echo LanguageHelper::t('filters.max'); ?>" 
                       value="<?php echo htmlspecialchars($priceTo); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <button type="submit" 
                        class="w-full px-4 py-2 bg-teal-600 text-white text-sm font-semibold rounded hover:bg-teal-700 transition">
                    <?php echo LanguageHelper::t('filters.apply_filter'); ?>
                </button>
                <?php if (!empty($priceFrom) || !empty($priceTo)): ?>
                    <a href="<?php 
                        $clearParams = $_GET;
                        unset($clearParams['price_from'], $clearParams['price_to']);
                        echo '/SHooad/public/customer/products?' . http_build_query($clearParams);
                    ?>" class="block w-full text-center px-4 py-2 text-sm text-gray-600 hover:text-teal-600 transition">
                        <?php echo LanguageHelper::t('filters.clear_price'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Brands Filter (Collapsible) -->
    <?php if (!empty($filters['brands'])): ?>
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
        <button type="button" 
                onclick="this.parentElement.querySelector('.brands-list').classList.toggle('hidden')"
                class="w-full flex items-center justify-between text-base font-bold text-gray-900 mb-3">
            <span><?php echo LanguageHelper::t('filters.brands'); ?></span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div class="brands-list hidden space-y-1 max-h-64 overflow-y-auto">
            <?php foreach (array_slice($filters['brands'], 0, 10) as $brand): ?>
                <?php 
                    $isSelected = ($selectedBrand === $brand['name']);
                    $queryParams = $_GET;
                    if ($isSelected) {
                        unset($queryParams['brand']);
                    } else {
                        $queryParams['brand'] = $brand['name'];
                    }
                    unset($queryParams['page']);
                    $url = '/SHooad/public/customer/products?' . http_build_query($queryParams);
                ?>
                <a href="<?php echo htmlspecialchars($url); ?>" 
                   class="block px-3 py-2 rounded text-sm transition <?php echo $isSelected ? 'bg-teal-600 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <div class="flex items-center justify-between">
                        <span><?php echo htmlspecialchars($brand['name']); ?></span>
                        <span class="<?php echo $isSelected ? 'text-teal-100' : 'text-gray-400'; ?> text-xs">
                            (<?php echo $brand['count']; ?>)
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>