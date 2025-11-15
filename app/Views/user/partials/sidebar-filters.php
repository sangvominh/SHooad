<?php
// Load categories and brands from DB
$mysqli = new mysqli('localhost', 'root', '', 'SHooad');
$filters = ['categories' => [], 'brands' => [], 'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'], 'colors' => [], 'ratings' => []];
if (!$mysqli->connect_error) {
    // Top categories by sold_quantity
    $catSql = "SELECT c.id, c.name, COALESCE(SUM(p.sold_quantity),0) AS total_sold, COUNT(p.id) AS product_count
               FROM categories c
               LEFT JOIN products p ON p.category_id = c.id
               GROUP BY c.id, c.name
               ORDER BY total_sold DESC
               LIMIT 100";
    $cres = $mysqli->query($catSql);
    if ($cres) {
        while ($crow = $cres->fetch_assoc()) {
            $filters['categories'][] = ['id' => $crow['id'], 'name' => $crow['name'], 'count' => intval($crow['product_count']), 'sold' => intval($crow['total_sold'])];
        }
        $cres->free();
    }

    // Top brands by sold_quantity
    $brandSql = "SELECT COALESCE(p.brand, '') AS brand, COALESCE(SUM(p.sold_quantity),0) AS total_sold, COUNT(p.id) AS product_count
                 FROM products p
                 WHERE p.brand IS NOT NULL AND p.brand != ''
                 GROUP BY p.brand
                 ORDER BY total_sold DESC
                 LIMIT 100";
    $bres = $mysqli->query($brandSql);
    if ($bres) {
        while ($brow = $bres->fetch_assoc()) {
            $filters['brands'][] = ['name' => $brow['brand'], 'count' => intval($brow['product_count']), 'sold' => intval($brow['total_sold'])];
        }
        $bres->free();
    }

    // Colors - gather distinct colors (assuming comma-separated in products.colors)
    $colorSql = "SELECT DISTINCT TRIM(cval) AS color FROM (
                    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(p.colors, ',', nums.n), ',', -1) AS cval
                  FROM products p
                  JOIN (
                    SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
                  ) nums ON CHAR_LENGTH(p.colors) - CHAR_LENGTH(REPLACE(p.colors, ',', '')) >= nums.n-1
                  WHERE p.colors IS NOT NULL AND p.colors != ''
                  ) t WHERE TRIM(cval) != '' LIMIT 100";
    $cres2 = $mysqli->query($colorSql);
    if ($cres2) {
        while ($crow2 = $cres2->fetch_assoc()) {
            $filters['colors'][] = ['name' => $crow2['color'], 'code' => '#cccccc'];
        }
        $cres2->free();
    }

    // Ratings distribution
    $ratSql = "SELECT FLOOR(rating) AS stars, COUNT(*) AS cnt FROM products WHERE rating IS NOT NULL GROUP BY FLOOR(rating) ORDER BY stars DESC";
    $rres = $mysqli->query($ratSql);
    if ($rres) {
        while ($rrow = $rres->fetch_assoc()) {
            $filters['ratings'][] = ['stars' => intval($rrow['stars']), 'count' => intval($rrow['cnt'])];
        }
        $rres->free();
    }
}
?>

<div class="space-y-6">

    <form id="filterForm" method="get">
        <!-- <div id="filter-categories" class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
            <div class="space-y-3">
                <?php $catCount = count($filters['categories']);
                $showCats = array_slice($filters['categories'], 0, 10); ?>
                <?php foreach ($showCats as $category): ?>
                    <label class="flex items-center gap-3 cursor-pointer hover:text-teal-700 transition">
                        <input type="radio" name="category" value="<?php echo htmlspecialchars($category['name']); ?>" class="w-4 h-4 border-gray-300 rounded">
                        <span class="text-gray-700"><?php echo htmlspecialchars($category['name']); ?></span>
                        <span class="text-gray-400 text-sm ml-auto">(<?php echo $category['count']; ?>)</span>
                    </label>
                <?php endforeach; ?>
                <?php if ($catCount > 10): ?>
                    <div id="more-categories" class="hidden space-y-3">
                        <?php foreach (array_slice($filters['categories'], 10) as $category): ?>
                            <label class="flex items-center gap-3 cursor-pointer hover:text-teal-700 transition">
                                <input type="radio" name="category" value="<?php echo htmlspecialchars($category['name']); ?>" class="w-4 h-4 border-gray-300 rounded">
                                <span class="text-gray-700"><?php echo htmlspecialchars($category['name']); ?></span>
                                <span class="text-gray-400 text-sm ml-auto">(<?php echo $category['count']; ?>)</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <button id="see-more-cats" type="button" class="mt-3 text-sm text-teal-700">See More</button>
                <?php endif; ?>
            </div>
        </div> -->

        <!-- <div id="filter-brands" class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Brands</h3>
            <div class="space-y-3">
                <?php foreach (array_slice($filters['brands'], 0, 5) as $brand): ?>
                    <label class="flex items-center gap-3 cursor-pointer hover:text-teal-700 transition">
                        <input type="radio" name="brand" value="<?php echo htmlspecialchars($brand['name']); ?>" class="w-4 h-4 border-gray-300 rounded">
                        <span class="text-gray-700"><?php echo htmlspecialchars($brand['name']); ?></span>
                        <span class="text-gray-400 text-sm ml-auto">(<?php echo $brand['count']; ?>)</span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div> -->

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Price Range</h3>
            <div class="flex gap-2">
                <input type="number" name="price_from" placeholder="From" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                <input type="number" name="price_to" placeholder="To" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
            </div>

            <button type="submit" class="w-full mt-4 px-4 py-2 bg-teal-700 text-white font-semibold rounded hover:bg-teal-800 transition">
                Filter
            </button>
        </div>

        <!-- Categories Table -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
            <div class="grid grid-cols-1 gap-2">
                <?php foreach ($filters['categories'] as $category): ?>
                    <form method="get" style="display:inline;">
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category['name']); ?>">
                        <?php
                        // preserve other query params except category
                        foreach ($_GET as $key => $value) {
                            if ($key !== 'category') {
                                echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                            }
                        }
                        ?>
                        <button type="submit" class="w-full text-left px-3 py-2 border border-gray-200 rounded hover:bg-teal-50 transition flex items-center justify-between <?php echo (isset($_GET['category']) && $_GET['category'] == $category['name']) ? 'bg-teal-100 font-bold' : ''; ?>">
                            <span><?php echo htmlspecialchars($category['name']); ?></span>
                            <span class="text-gray-400 text-sm">(<?php echo $category['count']; ?>)</span>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </form>

    <!-- (Removed colors, sizes and ratings filters as requested) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('see-more-cats');
            if (btn) {
                btn.addEventListener('click', function() {
                    var more = document.getElementById('more-categories');
                    if (!more) return;
                    if (more.classList.contains('hidden')) {
                        more.classList.remove('hidden');
                        btn.textContent = 'See Less';
                    } else {
                        more.classList.add('hidden');
                        btn.textContent = 'See More';
                    }
                });
            }
            // Ẩn filter khi chọn category hoặc brand
            var catRadios = document.querySelectorAll('input[name="category"]');
            var brandRadios = document.querySelectorAll('input[name="brand"]');
            var catBox = document.getElementById('filter-categories');
            var brandBox = document.getElementById('filter-brands');
            catRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (radio.checked) {
                        brandBox.style.display = 'none';
                        catBox.style.display = '';
                    }
                });
            });
            brandRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (radio.checked) {
                        catBox.style.display = 'none';
                        brandBox.style.display = '';
                    }
                });
            });
        });
    </script>
</div>