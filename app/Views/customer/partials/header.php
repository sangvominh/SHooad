<?php
// Load language helper
require_once __DIR__ . '/../../../Helpers/LanguageHelper.php';
$lang = LanguageHelper::getCurrentLanguage();
?>
<header class="bg-white text-gray-900 shadow-md">
    <!-- Top Bar -->
    <div class="container mx-auto px-2 md:px-4 py-2 md:py-4 flex flex-wrap justify-between items-center gap-2">
        <!-- Logo -->
        <a href="/SHooad/public/" class="text-xl md:text-2xl font-bold whitespace-nowrap">
            SHooad
        </a>

        <!-- Search Bar -->
        <div class="flex-1 min-w-[180px] max-w-md md:max-w-none md:mx-8">
            <div class="flex relative" id="searchWrapper">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="<?= LanguageHelper::t('header.search_placeholder') ?>"
                    class="flex-1 px-2 py-1.5 md:px-4 md:py-2 text-gray-800 border border-gray-300 rounded-l-lg focus:outline-none text-xs md:text-base">
                <button class="px-2 md:px-4 bg-[#001F5D] rounded-r-lg hover:bg-[#003082]">
                    <i class="fa-solid fa-magnifying-glass text-white text-sm md:text-base"></i>
                </button>
                <!-- Realtime search dropdown -->
                <div id="searchDropdown" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-xl z-[9999] hidden max-h-80 overflow-auto">
                    <!-- Results rendered by JS -->
                </div>
            </div>
        </div>


        <!-- User Icons -->
        <?php
        // Lấy dữ liệu từ controller/service
        $isLoggedIn = $data['header']['isLoggedIn'] ?? false;
        $avatarPath = $data['header']['avatarPath'] ?? "/SHooad/public/assets/logo/default-avatar.png";
        $cartCount = $data['header']['cartCount'] ?? 0;
        $customerEmail = $data['header']['customerEmail'] ?? '';
        $customerName = $data['header']['customerName'] ?? '';
        ?>
        <div class="flex gap-3 md:gap-4 items-center shrink-0">
            
            <!-- Language Switcher -->
            <div class="relative group">
                <button class="flex items-center gap-1.5 px-2 md:px-3 py-1.5 border border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                    <i class="fas fa-globe text-gray-600 text-sm md:text-base"></i>
                    <span class="text-xs md:text-sm font-medium text-gray-700 uppercase"><?= $lang ?></span>
                    <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                </button>
                <div class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[9999]">
                    <a href="/SHooad/public/customer/set-language?lang=vi&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" 
                       class="flex items-center gap-2 px-4 py-2.5 hover:bg-gray-100 transition <?= $lang === 'vi' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' ?>">
                        <img src="https://flagcdn.com/w20/vn.png" alt="Tiếng Việt" class="w-5 h-3.5 object-cover">
                        <span class="text-sm">Tiếng Việt</span>
                        <?php if ($lang === 'vi'): ?>
                            <i class="fas fa-check text-xs ml-auto"></i>
                        <?php endif; ?>
                    </a>
                    <a href="/SHooad/public/customer/set-language?lang=en&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" 
                       class="flex items-center gap-2 px-4 py-2.5 hover:bg-gray-100 transition <?= $lang === 'en' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' ?>">
                        <img src="https://flagcdn.com/w20/us.png" alt="English" class="w-5 h-3.5 object-cover">
                        <span class="text-sm">English</span>
                        <?php if ($lang === 'en'): ?>
                            <i class="fas fa-check text-xs ml-auto"></i>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Cart -->
            <?php if ($isLoggedIn): ?>
                <a href="/SHooad/public/customer/cart">
                    <button id="cartBtn" class="relative hover:opacity-80 transition hover:scale-110">
                        <i class="fa-solid fa-cart-shopping text-gray-900 text-xl" id="cartIcon"></i>
                        <?php if ($cartCount > 0): ?>
                        <span id="cartBadge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </button>
                </a>
            <?php else: ?>
                <a href="/SHooad/public/customer/login">
                    <button id="cartBtn" class="relative hover:opacity-80 transition hover:scale-110">
                        <i class="fa-solid fa-cart-shopping text-gray-900 text-xl" id="cartIcon"></i>
                    </button>
                </a>
            <?php endif; ?>


            <!-- Wishlist -->
            <button class="relative hover:opacity-80 transition hover:scale-110 md:hover:scale-150 text-gray-900 hidden sm:block">
                <!-- <i class="fa-solid fa-heart"></i> -->
            </button>

            <!-- Account -->
            <?php if ($isLoggedIn): ?>
                <div class="relative" id="avatarContainer">
                    <a href="/SHooad/public/customer/profile" class="hover:opacity-80 transition flex items-center gap-2 px-3 py-1.5 rounded-full bg-white hover:border-blue-500" id="avatarMenuBtn">
                        <img src="<?= htmlspecialchars($avatarPath) ?>" alt="avatar" class="object-cover w-8 h-8 rounded-full" />
                        <span class="text-gray-900 font-medium text-sm hidden md:inline"><?= htmlspecialchars($customerName) ?></span>
                    </a>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-[9999] hidden border border-gray-200" id="avatarDropdown">
                        <div class="py-1">
                            <a href="/SHooad/public/customer/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition"><?= LanguageHelper::t('header.profile') ?></a>
                            <a href="/SHooad/public/customer/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition"><?= LanguageHelper::t('header.logout') ?></a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="/SHooad/public/customer/profile">
                    <button class="hover:opacity-80 transition hover:scale-110">
                        <i class="fa-solid fa-user text-gray-900 text-xl"></i>
                    </button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
<script src="/SHooad/public/assets/js/customer/avatar-dropdown.js"></script>
<script src="/SHooad/public/assets/js/customer/search.js"></script>
<script>
    // Simple shake animation for cart icon
    function shakeCartIcon() {
        var icon = document.getElementById('cartIcon');
        if (!icon) return;
        icon.classList.add('animate-shake');
        setTimeout(function() {
            icon.classList.remove('animate-shake');
        }, 600);
    }

    // Update cart badge
    function updateCartBadge(newCount) {
        var badge = document.getElementById('cartBadge');
        if (!badge) {
            // Create badge if doesn't exist
            var cartBtn = document.getElementById('cartBtn');
            if (cartBtn) {
                badge = document.createElement('span');
                badge.id = 'cartBadge';
                badge.className = 'absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5';
                cartBtn.appendChild(badge);
            } else {
                return;
            }
        }
        badge.textContent = newCount;
        if (newCount > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }
    
    // Make functions globally accessible
    window.shakeCartIcon = shakeCartIcon;
    window.updateCartBadge = updateCartBadge;
</script>
<style>
    @keyframes shake {
        0% {
            transform: translateX(0);
        }

        20% {
            transform: translateX(-4px);
        }

        40% {
            transform: translateX(4px);
        }

        60% {
            transform: translateX(-4px);
        }

        80% {
            transform: translateX(4px);
        }

        100% {
            transform: translateX(0);
        }
    }

    .animate-shake {
        animation: shake 0.6s;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
</style>
</header>