<?php
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
LanguageHelper::init();
?>

<!-- Product Description Section -->
<div class="mt-12 border-t pt-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php echo LanguageHelper::t('product.description_title'); ?></h2>
    
    <!-- Main Description -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
            <?php echo htmlspecialchars($product['description']); ?>
        </p>
    </div>
</div>
