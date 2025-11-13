<nav class="bg-teal-700 border-t border-teal-600">
    <?php
    $cats = [];
    $brands = [];
    $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
    if (!$mysqli->connect_error) {
        $catSql = "SELECT c.name, COALESCE(SUM(p.sold_quantity),0) AS total_sold, COUNT(p.id) AS product_count
                   FROM categories c
                   LEFT JOIN products p ON p.category_id = c.id
                   GROUP BY c.id, c.name
                   ORDER BY total_sold DESC
                   LIMIT 10";
        $cres = $mysqli->query($catSql);
        if ($cres) {
            while ($r = $cres->fetch_assoc()) $cats[] = $r;
            $cres->free();
        }

        $brandSql = "SELECT p.brand AS name, COALESCE(SUM(p.sold_quantity),0) AS total_sold, COUNT(p.id) AS product_count
                     FROM products p
                     WHERE p.brand IS NOT NULL AND p.brand != ''
                     GROUP BY p.brand
                     ORDER BY total_sold DESC
                     LIMIT 10";
        $bres = $mysqli->query($brandSql);
        if ($bres) {
            while ($r = $bres->fetch_assoc()) $brands[] = $r;
            $bres->free();
        }
        $mysqli->close();
    }
    ?>
    <div class="container mx-auto px-4">
        <ul class="flex gap-8 text-white text-sm font-medium justify-center">
            <!-- Home Dropdown -->
            <li>
                <a href="/SHooad/public/user" class="py-3 hover:text-yellow-300 transition block">Home</a>
            </li>
            <!-- Dropdown Menu -->
            <div class="absolute left-0 mt-0 w-48 bg-white text -gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 first:rounded-t transition">Home Main</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 transition">Home Minimal</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 last:rounded-b transition">Home Classic</a>
            </div>
            </li>

            <!-- Category Dropdown -->
            <li class="relative group">
                <button class="py-3 hover:text-yellow-300 transition flex items-center gap-1">
                    Category
                    <svg class="w-4 h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-48 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <?php if (count($cats) === 0): ?>
                        <a href="/SHooad/app/Views/user/products.php" class="block px-4 py-2 hover:bg-gray-100 first:rounded-t transition">All Products</a>
                    <?php else: ?>
                        <?php foreach ($cats as $i => $c): ?>
                            <a href="/SHooad/app/Views/user/products.php?category=<?php echo urlencode($c['name']); ?>" class="block px-4 py-2 hover:bg-gray-100 <?php echo $i === 0 ? 'first:rounded-t' : ''; ?> transition"><?php echo htmlspecialchars($c['name']); ?></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </li>

            <!-- Brand Dropdown -->
            <li class="relative group">
                <button class="py-3 hover:text-yellow-300 transition flex items-center gap-1">
                    Brand
                    <svg class="w-4 h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-48 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <?php if (count($brands) === 0): ?>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 first:rounded-t transition">#</a>
                    <?php else: ?>
                        <?php foreach ($brands as $i => $b): ?>
                            <a href="/SHooad/app/Views/user/products.php?brand=<?php echo urlencode($b['name']); ?>" class="block px-4 py-2 hover:bg-gray-100 <?php echo $i === 0 ? 'first:rounded-t' : ''; ?> transition"><?php echo htmlspecialchars($b['name']); ?></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </li>

            <!-- Products Dropdown -->
            <li class="relative group">
                <button class="py-3 hover:text-yellow-300 transition flex items-center gap-1">
                    Products
                    <svg class="w-4 h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-48 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="/SHooad/app/Views/user/products.php?sort=latest" class="block px-4 py-2 hover:bg-gray-100 first:rounded-t transition">All Products</a>
                    <a href="/SHooad/app/Views/user/products.php?sort=new-arrivals" class="block px-4 py-2 hover:bg-gray-100 transition">New Arrivals</a>
                    <a href="/SHooad/app/Views/user/products.php?sort=sale" class="block px-4 py-2 hover:bg-gray-100 transition">Sale Items</a>
                    <a href="/SHooad/app/Views/user/products.php?sort=best-sellers" class="block px-4 py-2 hover:bg-gray-100 last:rounded-b transition">Best Sellers</a>
                </div>
            </li>

            <!-- About Dropdown -->
            <li class="relative group">
                <button class="py-3 hover:text-yellow-300 transition flex items-center gap-1">
                    About
                    <svg class="w-4 h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-48 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 first:rounded-t transition">About Us</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 transition">Our Story</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 last:rounded-b transition">Contact</a>
                </div>
            </li>

            <!-- Shop -->
            <li>
                <a href="#" class="py-3 hover:text-yellow-300 transition block">Shop</a>
            </li>

            <!-- Pages Dropdown -->
            <li class="relative group">
                <button class="py-3 hover:text-yellow-300 transition flex items-center gap-1">
                    Pages
                    <svg class="w-4 h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-48 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 first:rounded-t transition">Blog</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 transition">FAQ</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 last:rounded-b transition">Privacy Policy</a>
                </div>
            </li>
        </ul>
    </div>
</nav>