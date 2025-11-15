<!-- Page Header Section -->
<?php
// Get filter params from query
$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
$selectedBrand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'latest';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 9;

// Determine page title
$pageTitle = 'All Products';
if ($sort === 'new-arrivals') {
    $pageTitle = 'New Arrivals';
} elseif ($sort === 'sale') {
    $pageTitle = 'Sale';
} elseif ($sort === 'best-sellers') {
    $pageTitle = 'Best Sellers';
} elseif (!empty($selectedCategory) && strtolower($selectedCategory) !== 'all products' && strtolower($selectedCategory) !== 'all') {
    $pageTitle = $selectedCategory;
}

// Get products from data passed by controller
$products = $data['products'] ?? [];
$totalCount = $data['totalCount'] ?? 0;
$offset = ($page - 1) * $perPage;
?>

<div class="bg-gray-50 py-12 text-center">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($pageTitle . (!empty($selectedBrand) ? ' - ' . $selectedBrand : '')); ?></h1>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div style="position:sticky;top:32px;z-index:10;">
                <?php include __DIR__ . '/partials/sidebar-filters.php'; ?>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Sorting & Results Info -->
            <div class="flex justify-between items-center mb-8">
                <p class="text-gray-600">
                    Showing
                    <span class="font-semibold">
                        <?php
                        $start = $totalCount > 0 ? ($offset + 1) : 0;
                        $end = min($offset + $perPage, $totalCount);
                        echo $start . '-' . $end . ' of ' . $totalCount . ' results';
                        ?>
                    </span>
                </p>
                <div>
                    <form method="get" id="sortForm">
                        <?php
                        // preserve other query params
                        $qs = $_GET;
                        ?>
                        <select name="sort" class="px-4 py-2 border border-gray-300 rounded hover:border-teal-700 transition" onchange="document.getElementById('sortForm').submit()">
                            <option value="latest" <?php if ($sort === 'latest') echo 'selected'; ?>>Sort by latest</option>
                            <option value="price-asc" <?php if ($sort === 'price-asc') echo 'selected'; ?>>Sort by price: low to high</option>
                            <option value="price-desc" <?php if ($sort === 'price-desc') echo 'selected'; ?>>Sort by price: high to low</option>
                            <option value="rating-asc" <?php if ($sort === 'rating-asc') echo 'selected'; ?>>Sort by rating: low to high</option>
                            <option value="rating-desc" <?php if ($sort === 'rating-desc') echo 'selected'; ?>>Sort by rating: high to low</option>
                            <option value="best-sellers" <?php if ($sort === 'best-sellers') echo 'selected'; ?>>Sort by popularity</option>
                        </select>
                        <?php
                        foreach ($qs as $key => $value) {
                            if ($key !== 'sort') {
                                echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                            }
                        }
                        ?>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <?php include __DIR__ . '/partials/products-grid.php'; ?>

            <!-- Load More Button -->
            <?php if ($totalCount > ($page * $perPage)): ?>
                <div class="flex justify-center mt-12">
                    <?php $nextPage = $page + 1; ?>
                    <a href="?<?php
                                $qs = $_GET;
                                $qs['page'] = $nextPage;
                                echo http_build_query($qs);
                                ?>" class="px-8 py-3 bg-teal-700 text-white font-semibold rounded hover:bg-teal-800 transition">Load More</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
