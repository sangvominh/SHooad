<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Product detail page
session_start();
require_once __DIR__ . '/../../Models/Product.php';
// require_once __DIR__ . '/../../Core/Database.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$productId) {
    header('Location: /SHooad/app/Views/user/home.php');
    exit();
}

try {
    // Use PDO here because Product model expects PDO; fetch product directly
    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT p.*, sh.name as shop_name, sh.id as shop_id,
                GROUP_CONCAT(DISTINCT pi.filename) as images,
                p.colors as colors,
                p.sizes as sizes
            FROM products p
            LEFT JOIN shops sh ON p.shop_id = sh.id
            LEFT JOIN product_images pi ON p.id = pi.product_id
            WHERE p.id = :pid AND p.status = 'active'
            GROUP BY p.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pid' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: /SHooad/app/Views/user/home.php');
        exit();
    }

    // Process images: lấy ảnh sản phẩm, ảnh đầu tiên làm ảnh chính
    $imgStmt = $pdo->prepare('SELECT filename FROM product_images WHERE product_id = :pid ORDER BY id ASC');
    $imgStmt->execute([':pid' => $productId]);
    $imgRows = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
    $product['images'] = [];
    $product['main_image'] = '/SHooad/public/assets/products/default.jpg';
    foreach ($imgRows as $img) {
        $url = '/SHooad/public/assets/products/' . $img['filename'];
        $product['images'][] = $url;
    }
    // Lấy ảnh đầu tiên làm ảnh chính
    if (count($product['images']) > 0) {
        $product['main_image'] = $product['images'][0];
    }
    $product['thumbnail_images'] = array_values(array_filter($product['images'], function($img) use ($product) {
        return $img !== $product['main_image'];
    }));

    // Color helper
    $getColorCode = function(string $colorName): string {
        $colorMap = [
            'Black' => '#000000',
            'White' => '#FFFFFF',
            'Red' => '#FF0000',
            'Green' => '#008000',
            'Blue' => '#0000FF',
            'Yellow' => '#FFFF00',
            'Purple' => '#800080',
            'Pink' => '#FFC0CB',
            'Orange' => '#FFA500',
            'Brown' => '#A52A2A',
            'Gray' => '#808080'
        ];
        return $colorMap[$colorName] ?? '#000000';
    };

    // Parse colors (stored as comma-separated values like "Red, Blue, Green")
    $rawColors = $product['colors'] ?? '';
    $colorList = [];
    if (strlen(trim((string)$rawColors)) > 0) {
        $parts = array_map('trim', explode(',', $rawColors));
        $parts = array_filter($parts, function($v) { return $v !== '' && $v !== null; });
        $parts = array_unique($parts);
        $colorList = array_values($parts);
    }
    $product['colors'] = array_map(function($color) use ($getColorCode) {
        $name = (string)$color;
        // If the value is already a hex code, use it directly
        $trim = trim($name);
        if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $trim)) {
            $code = $trim;
        } else {
            // normalize for lookup (e.g., "white" -> "White")
            $normalized = ucwords(strtolower($trim));
            $code = $getColorCode($normalized);
        }
        return [
            'name' => $name,
            'code' => $code,
            'active' => false
        ];
    }, $colorList);
    if (!empty($product['colors'])) {
        $product['colors'][0]['active'] = true;
    }

    // Parse sizes (stored as comma-separated values like "S,M,L" or "40,41,42")
    $rawSizes = $product['sizes'] ?? '';
    $sizeList = [];
    if (strlen(trim((string)$rawSizes)) > 0) {
        $parts = array_map('trim', explode(',', $rawSizes));
        $parts = array_filter($parts, function($v) { return $v !== '' && $v !== null; });
        $parts = array_unique($parts);
        $sizeList = array_values($parts);
    }
    $product['sizes'] = $sizeList;

    // Defaults - rating, reviews_count, features, note not in current schema
    $product['rating'] = 0;
    $product['reviews_count'] = 0;
    $product['features'] = [];
    $product['note'] = '';
} catch (Exception $e) {
    error_log($e->getMessage());
    header('Location: /SHooad/app/Views/user/home.php');
    exit();
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Pursuit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
</head>
<body class="bg-white" data-stock="<?php echo htmlspecialchars($product['stock'] ?? 0); ?>" data-product-id="<?php echo $productId; ?>">
    <!-- Header & Navigation -->
    <?php include 'partials/header.php'; ?>
    
    <!-- Product Detail Container -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Gallery -->
            <?php include 'partials/product-gallery.php'; ?>
            
            <!-- Product Info -->
            <?php include 'partials/product-info.php'; ?>
        </div>
        
        <!-- Product Description -->
        <?php include 'partials/product-description.php'; ?>
    </div>
    
    <!-- Product Rating -->
    <?php include 'partials/product-rating.php'; ?>
    
    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="/SHooad/app/Views/user/js/dropdown.js"></script>
    <script src="/SHooad/app/Views/user/js/product-gallery.js"></script>
</body>
</html>
