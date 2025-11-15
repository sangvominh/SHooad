<nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-md">
    <?php
    // Lấy dữ liệu từ controller/service
    $cats = $data['navigation']['categories'] ?? [];
    $brands = $data['navigation']['brands'] ?? [];
    
    // Load breadcrumb helper
    require_once $_SERVER['DOCUMENT_ROOT'] . '/SHooad/app/Helpers/BreadcrumbHelper.php';
    ?>
    <div class="container mx-auto px-2 md:px-4">
        <div class="flex items-center justify-between">
            <!-- Breadcrumb Navigation - Left aligned -->
            <div class="hidden md:block py-2 md:py-3 min-w-0 flex-shrink">
                <?php echo BreadcrumbHelper::render(); ?>
            </div>
            
            <!-- Center Navigation Items -->
            <ul class="flex flex-wrap gap-2 md:gap-6 lg:gap-8 text-gray-900 text-xs md:text-sm font-medium justify-center items-center flex-1">
                <!-- Search Icon (visible on scroll) -->
                <li id="navSearchIcon" class="opacity-0 transition-opacity duration-300 shrink-0">
                    <button onclick="scrollToSearch()" class="py-2 md:py-3 hover:text-[#001F5D] transition flex items-center gap-1" title="Search">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </li>
                
                <!-- Home Dropdown - Hidden on smallest screens -->
                <li class="shrink-0 hidden lg:block">
                    <a href="/SHooad/public/customer" class="py-2 md:py-3 hover:text-[#001F5D] transition block">Home</a>
                </li>

            <!-- Category Dropdown -->
            <li class="relative group shrink-0">
                <button class="py-2 md:py-3 hover:text-[#001F5D] transition flex items-center gap-0.5 md:gap-1 whitespace-nowrap">
                    Category
                    <svg class="w-3 h-3 md:w-4 md:h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-64 max-h-96 overflow-y-auto bg-white text-gray-800 rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100]">
                    <div class="py-2">
                        <a href="/SHooad/public/customer/products" class="block px-4 py-2.5 hover:bg-gray-100 font-semibold text-[#001F5D]">All Categories</a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <?php if (count($cats) === 0): ?>
                            <span class="block px-4 py-2 text-gray-500 text-sm">No categories</span>
                        <?php else: ?>
                            <?php foreach ($cats as $c): ?>
                                <a href="/SHooad/public/customer/products?category=<?php echo urlencode($c['name']); ?>" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors"><?php echo htmlspecialchars($c['name']); ?></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </li>

            <!-- Brand Dropdown -->
            <li class="relative group shrink-0">
                <button class="py-2 md:py-3 hover:text-[#001F5D] transition flex items-center gap-0.5 md:gap-1 whitespace-nowrap">
                    Brand
                    <svg class="w-3 h-3 md:w-4 md:h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-64 max-h-96 overflow-y-auto bg-white text-gray-800 rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100]">
                    <div class="py-2">
                        <a href="/SHooad/public/customer/products" class="block px-4 py-2.5 hover:bg-gray-100 font-semibold text-[#001F5D]">All Brands</a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <?php if (count($brands) === 0): ?>
                            <span class="block px-4 py-2 text-gray-500 text-sm">No brands</span>
                        <?php else: ?>
                            <?php foreach ($brands as $b): ?>
                                <a href="/SHooad/public/customer/products?brand=<?php echo urlencode($b['name']); ?>" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors"><?php echo htmlspecialchars($b['name']); ?></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </li>

            <!-- Products Dropdown -->
            <li class="relative group shrink-0">
                <button class="py-2 md:py-3 hover:text-[#001F5D] transition flex items-center gap-0.5 md:gap-1 whitespace-nowrap">
                    Products
                    <svg class="w-3 h-3 md:w-4 md:h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-56 bg-white text-gray-800 rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100]">
                    <div class="py-2">
                        <a href="/SHooad/public/customer/products?sort=latest" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors font-semibold">All Products</a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <a href="/SHooad/public/customer/products?sort=new-arrivals" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">New Arrivals</a>
                        <a href="/SHooad/public/customer/products?sort=best-sellers" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Best Sellers</a>
                        <a href="/SHooad/public/customer/products?sort=sale" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Sale Items</a>
                        <a href="/SHooad/public/customer/products?sort=trending" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Trending</a>
                        <a href="/SHooad/public/customer/products?sort=featured" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Featured</a>
                    </div>
                </div>
            </li>

            <!-- About Dropdown - Hidden on small/medium screens -->
            <li class="relative group shrink-0 hidden xl:block">
                <button class="py-2 md:py-3 hover:text-[#001F5D] transition flex items-center gap-0.5 md:gap-1 whitespace-nowrap">
                    About
                    <svg class="w-3 h-3 md:w-4 md:h-4 transition transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="absolute left-0 mt-0 w-52 bg-white text-gray-800 rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[100]">
                    <div class="py-2">
                        <a href="#about-us" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">About Us</a>
                        <a href="#our-story" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Our Story</a>
                        <a href="#contact" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Contact</a>
                        <a href="#faq" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">FAQ</a>
                        <a href="#terms" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Terms & Conditions</a>
                        <a href="#privacy" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-[#001F5D] transition-colors">Privacy Policy</a>
                    </div>
                </div>
            </li>
        </ul>
        
        <!-- Right spacing to balance layout -->
        <div class="hidden md:block min-w-0 flex-shrink"></div>
        </div>
    </div>
</nav>