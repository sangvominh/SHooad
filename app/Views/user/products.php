<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

<body class="bg-white">
    <!-- Header -->
    <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
    <?php include 'partials/header.php'; ?>

    <!-- Page Header Section -->
    <?php
    if (session_status() == PHP_SESSION_NONE) session_start();

    // Filters from query
    $selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
    $selectedBrand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
    $priceFrom = isset($_GET['price_from']) ? floatval($_GET['price_from']) : 0;
    $priceTo = isset($_GET['price_to']) ? floatval($_GET['price_to']) : 0;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $perPage = 9;
    $offset = ($page - 1) * $perPage;

    // Sort and Build base query and count
    $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'latest';

    // Determine page title based on sort
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
    $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
    if ($mysqli->connect_error) {
        $totalCount = 0;
        $products = [];
    } else {
        $where = ["p.status = 'active'"];
        if (!empty($selectedBrand)) {
            $where[] = "p.brand = '" . $mysqli->real_escape_string($selectedBrand) . "'";
        }
        if (!empty($selectedCategory)) {
            $catName = $mysqli->real_escape_string($selectedCategory);
            $catRes = $mysqli->query("SELECT id FROM categories WHERE name = '" . $catName . "' LIMIT 1");
            if ($catRes && $catRow = $catRes->fetch_assoc()) {
                $catId = intval($catRow['id']);
                $where[] = "p.category_id = " . $catId;
            }
        }
        if ($priceFrom > 0) {
            $where[] = "p.price >= " . $priceFrom;
        }
        if ($priceTo > 0) {
            $where[] = "p.price <= " . $priceTo;
        }

        // apply sort-specific filters / order
        // Sắp xếp theo sort
        switch ($sort) {
            case 'latest':
                $orderSql = 'ORDER BY p.created_at DESC';
                break;
            case 'price-asc':
                $orderSql = 'ORDER BY p.price ASC';
                break;
            case 'price-desc':
                $orderSql = 'ORDER BY p.price DESC';
                break;
            case 'rating-asc':
                $orderSql = 'ORDER BY p.rating ASC';
                break;
            case 'rating-desc':
                $orderSql = 'ORDER BY p.rating DESC';
                break;
            case 'new-arrivals':
                $where[] = "p.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                $orderSql = 'ORDER BY p.created_at DESC';
                break;
            case 'sale':
                $where[] = "p.original_price IS NOT NULL AND p.original_price <> p.price";
                $orderSql = 'ORDER BY (p.original_price - p.price) DESC';
                break;
            case 'best-sellers':
                $orderSql = 'ORDER BY p.sold_quantity DESC';
                break;
            default:
                $orderSql = 'ORDER BY p.created_at DESC';
        }

        $whereSql = '';
        if (!empty($where)) $whereSql = 'WHERE ' . implode(' AND ', $where);

        // total count
        $countSql = "SELECT COUNT(*) AS cnt FROM products p " . $whereSql;
        $countRes = $mysqli->query($countSql);
        $totalCount = 0;
        if ($countRes && $r = $countRes->fetch_assoc()) $totalCount = intval($r['cnt']);

        // products list with image join
        $sql = "SELECT p.id, p.name, p.thumbnail_url, p.price, p.original_price, p.stock, p.rating, p.reviews_count, p.sold_quantity, p.brand, c.name AS category_name, pi.filename AS image_file
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1
        " . $whereSql . "
        " . $orderSql . "
        LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

        $res = $mysqli->query($sql);
        $products = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $thumbnail = '';
                if (!empty($row['image_file'])) {
                    $thumbnail = '/SHooad/public/assets/products/' . ltrim($row['image_file'], '/');
                } elseif (!empty($row['thumbnail_url'])) {
                    $thumb = $row['thumbnail_url'];
                    if (!preg_match('#^(https?://|/)#i', $thumb)) $thumb = '/SHooad/public/assets/products/' . ltrim($thumb, '/');
                    $thumbnail = $thumb;
                } else {
                    $thumbnail = '/SHooad/public/assets/logo/default-avatar.png';
                }

                $products[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'thumbnail_url' => $thumbnail,
                    'price' => $row['price'],
                    'original_price' => $row['original_price'],
                    'stock' => $row['stock'],
                    'rating' => $row['rating'],
                    'reviews_count' => $row['reviews_count'],
                    'category' => $row['category_name'],
                    'brand' => $row['brand'],
                    'sold_quantity' => $row['sold_quantity']
                ];
            }
            $res->free();
        }
    }
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
                    <?php include 'partials/sidebar-filters.php'; ?>
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
                <?php $products = isset($products) ? $products : [];
                include 'partials/products-grid.php'; ?>

                <!-- Load More Button -->
                <?php if ($totalCount > $perPage): ?>
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

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
</body>

</html>