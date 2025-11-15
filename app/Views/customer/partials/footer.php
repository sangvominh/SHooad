<?php
// Load language helper
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
?>

<footer class="bg-gray-900 text-gray-300">
    <!-- Footer Top Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About Us Column -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4"><?= LanguageHelper::t('footer.about_us') ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    <?= LanguageHelper::t('footer.about_desc') ?>
                </p>
                <div class="mt-4">
                    <a href="/SHooad/public/customer" class="text-2xl font-bold text-white">SHooad</a>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4"><?= LanguageHelper::t('footer.quick_links') ?></h3>
                <ul class="space-y-2">
                    <li><a href="/SHooad/public/customer" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('nav.home') ?></a></li>
                    <li><a href="/SHooad/public/customer/products" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('nav.products') ?></a></li>
                    <li><a href="/SHooad/public/customer/cart" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('header.cart') ?></a></li>
                    <li><a href="/SHooad/public/customer/orders" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('footer.order_history') ?></a></li>
                </ul>
            </div>

            <!-- Customer Service Column -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4"><?= LanguageHelper::t('footer.customer_service') ?></h3>
                <ul class="space-y-2">
                    <li><a href="#contact" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('footer.contact_us') ?></a></li>
                    <li><a href="/SHooad/public/customer/profile" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('footer.my_account') ?></a></li>
                    <li><a href="#terms" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('footer.terms') ?></a></li>
                    <li><a href="#privacy" class="text-gray-400 hover:text-white transition-colors"><?= LanguageHelper::t('footer.privacy') ?></a></li>
                </ul>
            </div>

            <!-- Follow Us Column -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4"><?= LanguageHelper::t('footer.follow_us') ?></h3>
                <div class="flex gap-3">
                    <a href="#facebook" title="Facebook" class="bg-[#001F5D] hover:bg-[#003082] text-white w-10 h-10 flex items-center justify-center rounded transition-colors">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#instagram" title="Instagram" class="bg-[#001F5D] hover:bg-[#003082] text-white w-10 h-10 flex items-center justify-center rounded transition-colors">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#twitter" title="Twitter" class="bg-[#001F5D] hover:bg-[#003082] text-white w-10 h-10 flex items-center justify-center rounded transition-colors">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                </div>
            </div>
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
