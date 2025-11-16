<?php
// Get filter params from query
$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
$selectedBrand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'latest';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 18; // Tăng từ 9 lên 18 để phù hợp với grid 6 cột

// Get products from data passed by controller
$products = $data['products'] ?? [];
$totalCount = $data['totalCount'] ?? 0;
$offset = ($page - 1) * $perPage;
?>

<!-- Main Content -->
<div class="max-w-screen-xl mx-auto px-2 sm:px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- Sidebar (hidden on small screens; toggled by Filter button) -->
        <div class="lg:col-span-1 hidden lg:block">
            <div style="position:sticky;top:24px;z-index:10;">
                <?php include __DIR__ . '/partials/sidebar-filters.php'; ?>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Compact Filter & Sort Bar (Shopee-like compact controls) -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                <div class="flex items-center gap-3">
                    <button id="openFiltersBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded text-sm lg:hidden">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 12h18M3 20h18"/></svg>
                        <span>Filter</span>
                    </button>
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-semibold">
                        <?php
                        $start = $totalCount > 0 ? ($offset + 1) : 0;
                        $end = min($offset + $perPage, $totalCount);
                        echo $start . '-' . $end . ' of ' . $totalCount . ' results';
                        ?>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <form method="get" id="sortForm">
                        <?php $qs = $_GET; ?>
                        <select name="sort" class="px-3 py-2 border border-gray-300 rounded text-sm" onchange="document.getElementById('sortForm').submit()">
                            <option value="latest" <?php if ($sort === 'latest') echo 'selected'; ?>>Sort by latest</option>
                            <option value="price-asc" <?php if ($sort === 'price-asc') echo 'selected'; ?>>Price: low to high</option>
                            <option value="price-desc" <?php if ($sort === 'price-desc') echo 'selected'; ?>>Price: high to low</option>
                            <option value="best-sellers" <?php if ($sort === 'best-sellers') echo 'selected'; ?>>Popularity</option>
                        </select>
                        <?php foreach ($qs as $key => $value) { if ($key !== 'sort' && $key !== 'page') echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">'; } ?>
                        <input type="hidden" name="page" value="1">
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <?php include __DIR__ . '/partials/products-grid.php'; ?>

            <!-- Numbered Pagination -->
            <?php 
                $totalPages = (int) ceil($totalCount / $perPage);
                if ($totalPages < 1) { $totalPages = 1; }
            ?>
            <?php if ($totalPages > 1): ?>
                <nav class="mt-12 flex justify-center" aria-label="Pagination">
                    <ul class="inline-flex items-center gap-1">
                        <?php 
                            $qs = $_GET; 
                            $makeUrl = function($p) use ($qs){ $qs['page'] = $p; return '?' . http_build_query($qs); };
                            $window = 2; // how many pages around current
                            $start = max(1, $page - $window);
                            $end = min($totalPages, $page + $window);
                            if ($start > 1) $start = max(1, min($start, 2));
                            if ($end < $totalPages) $end = min($totalPages, max($end, $totalPages-1));
                        ?>
                        <!-- Prev -->
                        <li>
                            <?php if ($page > 1): ?>
                                <a href="<?php echo htmlspecialchars($makeUrl($page-1)); ?>" class="px-3 py-2 border border-gray-300 bg-white rounded hover:bg-gray-50">Prev</a>
                            <?php else: ?>
                                <span class="px-3 py-2 border border-gray-200 bg-gray-100 text-gray-400 rounded cursor-not-allowed">Prev</span>
                            <?php endif; ?>
                        </li>

                        <!-- First page -->
                        <?php if ($start > 1): ?>
                            <li><a href="<?php echo htmlspecialchars($makeUrl(1)); ?>" class="px-3 py-2 border border-gray-300 bg-white rounded hover:bg-gray-50">1</a></li>
                            <?php if ($start > 2): ?><li><span class="px-2 text-gray-500">...</span></li><?php endif; ?>
                        <?php endif; ?>

                        <!-- Page window -->
                        <?php for ($p = $start; $p <= $end; $p++): ?>
                            <li>
                                <?php if ($p == $page): ?>
                                    <span class="px-3 py-2 border border-teal-600 bg-teal-600 text-white rounded"><?php echo $p; ?></span>
                                <?php else: ?>
                                    <a href="<?php echo htmlspecialchars($makeUrl($p)); ?>" class="px-3 py-2 border border-gray-300 bg-white rounded hover:bg-gray-50"><?php echo $p; ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endfor; ?>

                        <!-- Last page -->
                        <?php if ($end < $totalPages): ?>
                            <?php if ($end < $totalPages - 1): ?><li><span class="px-2 text-gray-500">...</span></li><?php endif; ?>
                            <li><a href="<?php echo htmlspecialchars($makeUrl($totalPages)); ?>" class="px-3 py-2 border border-gray-300 bg-white rounded hover:bg-gray-50"><?php echo $totalPages; ?></a></li>
                        <?php endif; ?>

                        <!-- Next -->
                        <li>
                            <?php if ($page < $totalPages): ?>
                                <a href="<?php echo htmlspecialchars($makeUrl($page+1)); ?>" class="px-3 py-2 border border-gray-300 bg-white rounded hover:bg-gray-50">Next</a>
                            <?php else: ?>
                                <span class="px-3 py-2 border border-gray-200 bg-gray-100 text-gray-400 rounded cursor-not-allowed">Next</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

        <!-- Mobile filter overlay (rendered once for small screens) -->
        <div id="mobileFilterOverlay" class="fixed inset-0 z-50 lg:hidden hidden">
            <div id="mobileFilterBackdrop" class="absolute inset-0 bg-black opacity-40"></div>
            <div class="absolute left-0 top-0 bottom-0 w-80 bg-white p-4 overflow-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Filter</h3>
                    <button id="closeFiltersBtn" class="px-2 py-1 text-sm text-gray-600">Close</button>
                </div>
                <?php include __DIR__ . '/partials/sidebar-filters.php'; ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var openBtn = document.getElementById('openFiltersBtn');
                var closeBtn = document.getElementById('closeFiltersBtn');
                var overlay = document.getElementById('mobileFilterOverlay');
                var backdrop = document.getElementById('mobileFilterBackdrop');
                if (openBtn && overlay) {
                    openBtn.addEventListener('click', function() { overlay.classList.remove('hidden'); document.body.style.overflow = 'hidden'; });
                }
                if (closeBtn && overlay) {
                    closeBtn.addEventListener('click', function() { overlay.classList.add('hidden'); document.body.style.overflow = ''; });
                }
                if (backdrop && overlay) {
                    backdrop.addEventListener('click', function() { overlay.classList.add('hidden'); document.body.style.overflow = ''; });
                }
            });
        </script>
