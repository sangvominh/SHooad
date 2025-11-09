<header class="bg-teal-700 text-white">
    <!-- Top Bar -->
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
         <a href="#" class="text-2xl font-bold">
            SHooad
         </a>
        
        <!-- Search Bar -->
        <div class="flex-1 mx-8">
        <div class="flex">
            <input 
            type="text" 
            placeholder="Search for anything" 
            class="flex-1 px-4 py-2 text-gray-800 border border-gray-300 rounded-l-lg focus:outline-none"
            >
            <button class="px-4 bg-[#FFD44D] rounded-r-lg hover:bg-gray-300">
            <i class="fa-solid fa-magnifying-glass text-gray-700"></i>
            </button>
        </div>
        </div>

        
        <!-- User Icons -->
        <?php
        // session_start();
        $isLoggedIn = isset($_SESSION['user']);
        $avatarPath = "/SHooad/public/assets/logo/default-avatar.png";
        if ($isLoggedIn) {
            // Giả sử $_SESSION['user'] là mảng chứa thông tin user, ví dụ: ['name'=>..., 'avatar'=>...]
            $user = $_SESSION['user'];
            if (!empty($user['avatar'])) {
                $avatarPath = $user['avatar'];
            }
        }
        ?>
        <div class="flex gap-4 items-center">
            <!-- Account -->
            <?php if ($isLoggedIn): ?>
                <div class="relative group">
                    <button class="hover:opacity-80 transition rounded-full border-2 border-white w-10 h-10 flex items-center justify-center overflow-hidden bg-white hover:scale-110" id="avatarMenuBtn">
                        <img src="<?= htmlspecialchars($avatarPath) ?>" alt="avatar" class="object-cover w-full h-full rounded-full" />
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg z-50 hidden group-hover:block" id="avatarDropdown">
                        <a href="/SHooad/app/Views/user/profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                        <a href="/SHooad/app/Views/user/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/SHooad/app/Views/user/login.php">
                    <button class="hover:opacity-80 transition hover:scale-150">
                        <i class="fa-solid fa-user"></i>
                    </button>
                </a>
            <?php endif; ?>
            
            
            <!-- Wishlist -->
            <button class="relative hover:opacity-80 transition hover:scale-150">
                <i class="fa-solid fa-heart"></i>
            </button>
            
            <!-- Cart -->
            <button class="relative hover:opacity-80 transition hover:scale-150">
                <i class="fa-solid fa-cart-shopping"></i>
            </button>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <?php include __DIR__ . '/navigation.php'; ?>
</header>
<script src="/SHooad/app/Views/user/js/avatar-dropdown.js"></script>
</header>
