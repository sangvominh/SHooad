<header class="bg-white text-gray-900 shadow-md">
    <!-- Top Bar -->
    <div class="container mx-auto px-2 md:px-4 py-2 md:py-4 flex flex-wrap justify-between items-center gap-2">
        <!-- Logo -->
        <a href="/SHooad/public/" class="text-xl md:text-2xl font-bold whitespace-nowrap">
            SHooad
        </a>

        <!-- Search Bar -->
        <div class="flex-1 min-w-[180px] max-w-md md:max-w-none md:mx-8">
            <div class="flex">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search..."
                    class="flex-1 px-2 py-1.5 md:px-4 md:py-2 text-gray-800 border border-gray-300 rounded-l-lg focus:outline-none text-xs md:text-base">
                <button class="px-2 md:px-4 bg-[#001F5D] rounded-r-lg hover:bg-[#003082]">
                    <i class="fa-solid fa-magnifying-glass text-white text-sm md:text-base"></i>
                </button>
            </div>
        </div>


        <!-- User Icons -->
        <?php
        // Lấy dữ liệu từ controller/service
        $isLoggedIn = $data['header']['isLoggedIn'] ?? false;
        $avatarPath = $data['header']['avatarPath'] ?? "/SHooad/public/assets/logo/default-avatar.png";
        $cartCount = $data['header']['cartCount'] ?? 0;
        $customerEmail = $data['header']['customerEmail'] ?? '';
        ?>
        <div class="flex gap-3 md:gap-4 items-center shrink-0">

            <!-- Cart -->
            <?php if ($isLoggedIn): ?>
                <a href="/SHooad/public/customer/cart">
                    <button id="cartBtn" class="relative hover:opacity-80 transition hover:scale-110 md:hover:scale-150">
                        <i class="fa-solid fa-cart-shopping text-gray-900 text-lg md:text-xl" id="cartIcon"></i>
                        <span id="cartBadge" class="absolute -top-2 -right-4 md:-top-3 md:-right-5 bg-[#001F5D] text-white text-xs font-bold rounded-full px-1.5 md:px-2 py-0.5"><?= $cartCount ?></span>
                    </button>
                </a>
            <?php else: ?>
                <a href="/SHooad/public/customer/login">
                    <button id="cartBtn" class="relative hover:opacity-80 transition hover:scale-110 md:hover:scale-150">
                        <i class="fa-solid fa-cart-shopping text-gray-900 text-lg md:text-xl" id="cartIcon"></i>
                    </button>
                </a>
            <?php endif; ?>


            <!-- Wishlist -->
            <button class="relative hover:opacity-80 transition hover:scale-110 md:hover:scale-150 text-gray-900 hidden sm:block">
                <!-- <i class="fa-solid fa-heart"></i> -->
            </button>

            <!-- Account -->
            <?php if ($isLoggedIn): ?>
                <div class="relative group">
                    <button class="hover:opacity-80 transition rounded-full border-2 border-gray-300 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center overflow-hidden bg-white hover:scale-110" id="avatarMenuBtn">
                        <img src="<?= htmlspecialchars($avatarPath) ?>" alt="avatar" class="object-cover w-full h-full rounded-full" />
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg z-50 hidden group-hover:block border border-gray-200" id="avatarDropdown">
                        <a href="/SHooad/public/customer/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                        <a href="/SHooad/public/customer/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/SHooad/public/customer/login">
                    <button class="hover:opacity-80 transition hover:scale-110 md:hover:scale-150">
                        <i class="fa-solid fa-user text-gray-900 text-lg md:text-xl"></i>
                    </button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
<script src="/SHooad/public/assets/js/user/avatar-dropdown.js"></script>
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
        if (!badge) return;
        badge.textContent = newCount;
        badge.classList.remove('hidden');
    }
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
</style>
</header>