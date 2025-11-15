<header class="bg-teal-700 text-white">
    <!-- Top Bar -->
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="/SHooad/public/" class="text-2xl font-bold">
            SHooad
        </a>

        <!-- Search Bar -->
        <div class="flex-1 mx-8">
            <div class="flex">
                <input
                    type="text"
                    placeholder="Search for anything"
                    class="flex-1 px-4 py-2 text-gray-800 border border-gray-300 rounded-l-lg focus:outline-none">
                <button class="px-4 bg-[#FFD44D] rounded-r-lg hover:bg-gray-300">
                    <i class="fa-solid fa-magnifying-glass text-gray-700"></i>
                </button>
            </div>
        </div>


        <!-- User Icons -->
        <?php
        // session_start();
        $isLoggedIn = isset($_SESSION['customer_id']);
        $avatarPath = "/SHooad/public/assets/logo/default-avatar.png";
        $cartCount = 0;
        if ($isLoggedIn) {
            $customerEmail = $_SESSION['customer_email'] ?? '';
            // Avatar support can be added later if needed
            
            // Get cart count for logged-in customer
            $customerId = intval($_SESSION['customer_id']);
            if ($customerId) {
                $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
                if (!$mysqli->connect_error) {
                    // Get cart_id first
                    $cartResult = $mysqli->query("SELECT id FROM carts WHERE customer_id = " . $customerId);
                    if ($cartResult && $cartResult->num_rows > 0) {
                        $cartRow = $cartResult->fetch_assoc();
                        $cartId = $cartRow['id'];
                        $result = $mysqli->query("SELECT SUM(quantity) AS total FROM cart_items WHERE cart_id = " . intval($cartId));
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $cartCount = intval($row['total']);
                        }
                        $result->free();
                        $cartResult->free();
                    }
                    $mysqli->close();
                }
            }
        }
        ?>
        <div class="flex gap-4 items-center">

            <!-- Cart -->
            <?php if ($isLoggedIn): ?>
                <a href="/SHooad/public/customer/cart">
                    <button id="cartBtn" class="relative hover:opacity-80 transition hover:scale-150">
                        <i class="fa-solid fa-cart-shopping" id="cartIcon"></i>
                        <span id="cartBadge" class="absolute -top-3 -right-5 bg-yellow-400 text-black text-xs font-bold rounded-full px-2 py-0.5"><?= $cartCount ?></span>
                    </button>
                </a>
            <?php else: ?>
                <a href="/SHooad/public/customer/login">
                    <button id="cartBtn" class="relative hover:opacity-80 transition hover:scale-150">
                        <i class="fa-solid fa-cart-shopping" id="cartIcon"></i>
                    </button>
                </a>
            <?php endif; ?>


            <!-- Wishlist -->
            <button class="relative hover:opacity-80 transition hover:scale-150">
                <!-- <i class="fa-solid fa-heart"></i> -->
            </button>

            <!-- Account -->
            <?php if ($isLoggedIn): ?>
                <div class="relative group">
                    <button class="hover:opacity-80 transition rounded-full border-2 border-white w-10 h-10 flex items-center justify-center overflow-hidden bg-white hover:scale-110" id="avatarMenuBtn">
                        <img src="<?= htmlspecialchars($avatarPath) ?>" alt="avatar" class="object-cover w-full h-full rounded-full" />
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg z-50 hidden group-hover:block" id="avatarDropdown">
                        <a href="/SHooad/public/customer/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                        <a href="/SHooad/public/customer/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/SHooad/public/customer/login">
                    <button class="hover:opacity-80 transition hover:scale-150">
                        <i class="fa-solid fa-user"></i>
                    </button>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Navigation Menu -->
    <?php include __DIR__ . '/navigation.php'; ?>
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