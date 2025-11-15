<?php
// Load language helper
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
?>

<footer class="bg-gray-900 text-gray-300">
    <!-- Footer Top Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            <?php foreach ($footer_columns as $column): ?>
                <?php if (isset($column['is_social']) && $column['is_social']): ?>
                    <!-- Social Links Column -->
                    <div>
                        <h3 class="text-white font-bold text-lg mb-4"><?php echo htmlspecialchars($column['title']); ?></h3>
                        <div class="flex gap-3">
                            <?php foreach ($column['socials'] as $social): ?>
                                <a href="<?php echo htmlspecialchars($social['href']); ?>" 
                                   title="<?php echo htmlspecialchars($social['label']); ?>"
                                   class="bg-[#001F5D] hover:bg-[#003082] text-white w-10 h-10 flex items-center justify-center rounded transition-colors">
                                    <?php 
                                    // Social media icons
                                    $iconMap = [
                                        'facebook' => 'fa-facebook-f',
                                        'instagram' => 'fa-instagram',
                                        'twitter' => 'fa-x-twitter'
                                    ];
                                    $iconClass = $iconMap[$social['icon']] ?? 'fa-link';
                                    echo '<i class="fa-brands ' . $iconClass . '"></i>';
                                    ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Regular Links Column -->
                    <div>
                        <h3 class="text-white font-bold text-lg mb-4"><?php echo htmlspecialchars($column['title']); ?></h3>
                        <ul class="space-y-2">
                            <?php foreach ($column['links'] as $link): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($link['href']); ?>" 
                                       class="text-gray-400 hover:text-white transition-colors">
                                        <?php echo htmlspecialchars($link['text']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Footer Bottom Section -->
    <div class="bg-yellow-100 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Logo -->
                <div>
                    <a href="/SHooad/public/customer" class="text-3xl font-bold text-gray-900">SHooad</a>
                </div>
                
                <!-- Payment Methods -->
                <div class="flex items-center gap-4 justify-center">
                    <span class="text-sm text-gray-700 font-medium flex items-center"><?= LanguageHelper::t('checkout.payment_method') ?>:</span>
                    <div class="flex items-center gap-6 text-3xl">
                        <i class="fa-brands fa-paypal text-blue-600"></i>
                        <i class="fa-brands fa-cc-visa text-blue-800"></i>
                        <i class="fa-brands fa-cc-mastercard text-red-600"></i>
                    </div>
                </div>            
                
                <!-- Copyright -->
                <div class="text-sm text-gray-700">
                    Copyright © 2025 SHooad <?= LanguageHelper::t('footer.all_rights_reserved') ?>
                </div>
            </div>
        </div>
    </div>
</footer>
