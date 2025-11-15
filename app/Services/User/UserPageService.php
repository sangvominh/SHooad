<?php
require_once __DIR__ . '/../ProductService.php';

class UserPageService {
    private $productService;

    public function __construct() {
        $this->productService = new ProductService();
    }

    public function getHomePageData(): array {
        return [
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData()
        ];
    }

    public function getNavigationData(): array {
        $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
        $cats = [];
        $brands = [];
        
        if (!$mysqli->connect_error) {
            // Lấy tất cả categories
            $cres = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");
            if ($cres) {
                while ($r = $cres->fetch_assoc()) $cats[] = $r;
                $cres->free();
            }

            // Lấy tất cả brands
            $bres = $mysqli->query("SELECT DISTINCT brand AS name FROM products WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
            if ($bres) {
                while ($r = $bres->fetch_assoc()) $brands[] = $r;
                $bres->free();
            }
            $mysqli->close();
        }
        
        return [
            'categories' => $cats,
            'brands' => $brands
        ];
    }

    public function getHeaderData(): array {
        $isLoggedIn = isset($_SESSION['customer_id']);
        $cartCount = 0;
        $customerName = '';
        
        if ($isLoggedIn) {
            $customerId = intval($_SESSION['customer_id']);
            if ($customerId) {
                $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
                if (!$mysqli->connect_error) {
                    // Get customer name
                    $customerResult = $mysqli->query("SELECT name FROM customers WHERE id = " . $customerId);
                    if ($customerResult && $customerResult->num_rows > 0) {
                        $customerRow = $customerResult->fetch_assoc();
                        $customerName = $customerRow['name'];
                        $customerResult->free();
                    }
                    
                    $cartResult = $mysqli->query("SELECT id FROM carts WHERE customer_id = " . $customerId);
                    if ($cartResult && $cartResult->num_rows > 0) {
                        $cartRow = $cartResult->fetch_assoc();
                        $cartId = $cartRow['id'];
                        // Count number of distinct items, not total quantity
                        $result = $mysqli->query("SELECT COUNT(*) AS total FROM cart_items WHERE cart_id = " . intval($cartId));
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
        
        return [
            'isLoggedIn' => $isLoggedIn,
            'avatarPath' => "/SHooad/public/assets/logo/default-avatar.png",
            'cartCount' => $cartCount,
            'customerEmail' => $_SESSION['customer_email'] ?? '',
            'customerName' => $customerName
        ];
    }

    public function getProductDetailData(int $productId): ?array {
        $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT p.*, 
                    sh.name as shop_name, 
                    sh.id as shop_id,
                    sh.description as shop_description,
                    (SELECT COUNT(*) FROM products WHERE shop_id = sh.id AND status = 'active') as shop_products_count,
                    GROUP_CONCAT(DISTINCT pi.filename) as images
                FROM products p
                LEFT JOIN shops sh ON p.shop_id = sh.id
                LEFT JOIN product_images pi ON p.id = pi.product_id
                WHERE p.id = :pid AND p.status = 'active'
                GROUP BY p.id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return null;
        }

        // Process images
        $imgStmt = $pdo->prepare('SELECT filename FROM product_images WHERE product_id = :pid ORDER BY id ASC');
        $imgStmt->execute([':pid' => $productId]);
        $imgRows = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $product['images'] = [];
        $product['main_image'] = '/SHooad/public/assets/products/default.jpg';
        
        foreach ($imgRows as $img) {
            $url = '/SHooad/public/assets/products/' . $img['filename'];
            $product['images'][] = $url;
        }

        if (count($product['images']) > 0) {
            $product['main_image'] = $product['images'][0];
        }

        $product['thumbnail_images'] = array_values(array_filter($product['images'], function($img) use ($product) {
            return $img !== $product['main_image'];
        }));

        // Get reviews and calculate rating
        $reviewStmt = $pdo->prepare('
            SELECT r.*, c.name as customer_name, c.email as customer_email
            FROM reviews r
            JOIN customers c ON r.customer_id = c.id
            WHERE r.product_id = :pid
            ORDER BY r.created_at DESC
        ');
        $reviewStmt->execute([':pid' => $productId]);
        $reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $product['reviews'] = $reviews;
        $product['reviews_count'] = count($reviews);
        
        // Calculate average rating
        if (count($reviews) > 0) {
            $totalRating = array_sum(array_column($reviews, 'rating'));
            $product['rating'] = round($totalRating / count($reviews), 1);
        } else {
            $product['rating'] = 0;
        }

        // Get colors and sizes from new tables
        $product['colors'] = $this->getProductColorsFromDB($productId);
        $product['sizes'] = $this->getProductSizesFromDB($productId);

        return $product;
    }

    /**
     * Get product colors from database
     */
    private function getProductColorsFromDB(int $productId): array {
        $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8', 'root', '');
        
        $stmt = $pdo->prepare("
            SELECT c.id, c.name, c.hex_code, pc.stock
            FROM product_colors pc
            JOIN colors c ON pc.color_id = c.id
            WHERE pc.product_id = ?
            ORDER BY c.name
        ");
        
        $stmt->execute([$productId]);
        $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($color) {
            return [
                'id' => $color['id'],
                'name' => $color['name'],
                'code' => $color['hex_code'],
                'stock' => $color['stock'],
                'active' => false
            ];
        }, $colors);
    }

    /**
     * Get product sizes from database
     */
    private function getProductSizesFromDB(int $productId): array {
        $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8', 'root', '');
        
        $stmt = $pdo->prepare("
            SELECT s.id, s.name, ps.stock
            FROM product_sizes ps
            JOIN sizes s ON ps.size_id = s.id
            WHERE ps.product_id = ?
            ORDER BY s.sort_order
        ");
        
        $stmt->execute([$productId]);
        $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($size) {
            return [
                'id' => $size['id'],
                'name' => $size['name'],
                'stock' => $size['stock']
            ];
        }, $sizes);
    }

    public function getProductDetailPageData(int $productId): ?array {
        $product = $this->getProductDetailData($productId);
        
        if (!$product) {
            return null;
        }
        
        return [
            'product' => $product,
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData()
        ];
    }

    public function getCartData(): array {
        return [
            'cart_items' => $this->getCartItemsData(),
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData(),
            'bodyClass' => 'bg-gray-50'
        ];
    }

    public function getProductsPageData(): array {
        // Get filter params
        $selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
        $selectedBrand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
        $priceFrom = isset($_GET['price_from']) ? floatval($_GET['price_from']) : 0;
        $priceTo = isset($_GET['price_to']) ? floatval($_GET['price_to']) : 0;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 9;
        $offset = ($page - 1) * $perPage;
        $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'latest';

        $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
        $products = [];
        $totalCount = 0;

        if (!$mysqli->connect_error) {
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

            // Sort logic
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
                case 'new-arrivals':
                    $where[] = "p.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                    $orderSql = 'ORDER BY p.created_at DESC';
                    break;
                case 'sale':
                    $where[] = "p.original_price IS NOT NULL AND p.original_price <> p.price";
                    $orderSql = 'ORDER BY (p.original_price - p.price) DESC';
                    break;
                case 'best-sellers':
                    $orderSql = 'ORDER BY p.sold DESC';
                    break;
                default:
                    $orderSql = 'ORDER BY p.created_at DESC';
            }

            $whereSql = '';
            if (!empty($where)) $whereSql = 'WHERE ' . implode(' AND ', $where);

            // Count total
            $countSql = "SELECT COUNT(*) AS cnt FROM products p " . $whereSql;
            $countRes = $mysqli->query($countSql);
            if ($countRes && $r = $countRes->fetch_assoc()) {
                $totalCount = intval($r['cnt']);
            }

            // Get products
            $sql = "SELECT p.id, p.name, p.price, p.original_price, p.stock, p.sold, p.brand, c.name AS category_name, pi.filename AS image_file
                    FROM products p
                    LEFT JOIN categories c ON c.id = p.category_id
                    LEFT JOIN product_images pi ON pi.product_id = p.id
                    " . $whereSql . "
                    GROUP BY p.id
                    " . $orderSql . "
                    LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

            $res = $mysqli->query($sql);
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $thumbnail = '';
                    if (!empty($row['image_file'])) {
                        $thumbnail = '/SHooad/public/assets/products/' . ltrim($row['image_file'], '/');
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
                        'rating' => 0,
                        'reviews_count' => 0,
                        'category' => $row['category_name'],
                        'brand' => $row['brand'],
                        'sold_quantity' => $row['sold'] ?? 0
                    ];
                }
                $res->free();
            }
            $mysqli->close();
        }

        return [
            'products' => $products,
            'totalCount' => $totalCount,
            'filters' => $this->getFiltersData(),
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData()
        ];
    }

    public function getFiltersData(): array {
        $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
        $filters = ['categories' => [], 'brands' => [], 'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'], 'colors' => [], 'ratings' => []];
        
        if (!$mysqli->connect_error) {
            // Top categories by sold
            $catSql = "SELECT c.id, c.name, COALESCE(SUM(p.sold),0) AS total_sold, COUNT(p.id) AS product_count
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

            // Top brands by sold
            $brandSql = "SELECT COALESCE(p.brand, '') AS brand, COALESCE(SUM(p.sold),0) AS total_sold, COUNT(p.id) AS product_count
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

            // Colors
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

            // Ratings distribution - Skip if rating column doesn't exist
            // $ratSql = "SELECT FLOOR(rating) AS stars, COUNT(*) AS cnt FROM products WHERE rating IS NOT NULL GROUP BY FLOOR(rating) ORDER BY stars DESC";
            // $rres = $mysqli->query($ratSql);
            // if ($rres) {
            //     while ($rrow = $rres->fetch_assoc()) {
            //         $filters['ratings'][] = ['stars' => intval($rrow['stars']), 'count' => intval($rrow['cnt'])];
            //     }
            //     $rres->free();
            // }

            $mysqli->close();
        }
        
        return $filters;
    }

    public function getCartItemsData(): array {
        $cart_items = [];
        if (isset($_SESSION['customer_id'])) {
            $customerId = intval($_SESSION['customer_id']);
            $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
            if (!$mysqli->connect_error) {
                $sql = "SELECT ci.id AS cart_item_id, ci.product_id, ci.color, ci.size, ci.quantity, ci.selected, p.name, p.price, p.original_price, p.stock, p.colors AS available_colors, p.sizes AS available_sizes, pi.filename AS image_file
                        FROM carts c
                        JOIN cart_items ci ON ci.cart_id = c.id
                        JOIN products p ON p.id = ci.product_id
                        LEFT JOIN product_images pi ON pi.product_id = p.id
                        WHERE c.customer_id = " . $customerId . "
                        GROUP BY ci.id
                        ORDER BY ci.id DESC";

                $res = $mysqli->query($sql);
                if ($res) {
                    while ($row = $res->fetch_assoc()) {
                        $thumb = $row['image_file'];
                        if (!empty($thumb)) {
                            $thumb = '/SHooad/public/assets/products/' . $thumb;
                        } else {
                            $thumb = '/SHooad/public/assets/logo/default-avatar.png';
                        }

                        $cart_items[] = [
                            'cart_item_id' => $row['cart_item_id'],
                            'id' => $row['product_id'],
                            'name' => $row['name'],
                            'image' => $thumb,
                            'price' => $row['price'],
                            'original_price' => $row['original_price'],
                            'size' => $row['size'],
                            'color' => $row['color'],
                            'quantity' => $row['quantity'],
                            'stock' => $row['stock'],
                            'available_colors' => $row['available_colors'],
                            'available_sizes' => $row['available_sizes'],
                            'selected' => $row['selected']
                        ];
                    }
                    $res->free();
                }
                $mysqli->close();
            }
        }
        return $cart_items;
    }
    
    public function getCheckoutData(): array {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $customerAddresses = [];
        
        if (isset($_SESSION['customer_id'])) {
            $customerId = intval($_SESSION['customer_id']);
            
            require_once __DIR__ . '/../../Models/Customer.php';
            $customerModel = new Customer();
            
            // Get customer addresses
            $customerAddresses = $customerModel->getCustomerAddresses($customerId);
        }
        
        return [
            'cart_items' => $this->getCartItemsData(),
            'customerAddresses' => $customerAddresses,
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData(),
            'bodyClass' => 'bg-gray-50'
        ];
    }

    public function getProfileData(): array {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $customer = null;
        $addresses = [];
        $orderStatusCounts = [];
        $recentOrders = [];
        
        if (isset($_SESSION['customer_id'])) {
            $customerId = intval($_SESSION['customer_id']);
            
            require_once __DIR__ . '/../../Models/Customer.php';
            require_once __DIR__ . '/../../Models/Order.php';
            
            $customerModel = new Customer();
            $orderModel = new Order();
            
            // Get customer info
            $customer = $customerModel->findById($customerId);
            
            // Get addresses
            $addresses = $customerModel->getCustomerAddresses($customerId);
            
            // Get order status counts
            $orderStatusCounts = $orderModel->getOrderStatusCounts($customerId);
            
            // Get recent orders (latest 5)
            $recentOrders = array_slice($orderModel->getOrdersByCustomer($customerId), 0, 5);
        }
        
        return [
            'customer' => $customer,
            'addresses' => $addresses,
            'orderStatusCounts' => $orderStatusCounts,
            'recentOrders' => $recentOrders,
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData(),
            'bodyClass' => 'bg-gray-50'
        ];
    }

    public function getOrdersPageData(): array {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $orders = [];
        $orderStatusCounts = [];
        $filterStatus = $_GET['status'] ?? null;
        
        if (isset($_SESSION['customer_id'])) {
            $customerId = intval($_SESSION['customer_id']);
            
            require_once __DIR__ . '/../../Models/Order.php';
            $orderModel = new Order();
            
            // Get order status counts
            $orderStatusCounts = $orderModel->getOrderStatusCounts($customerId);
            
            // Get orders (filtered or all)
            $orders = $orderModel->getOrdersByCustomer($customerId, $filterStatus);
        }
        
        return [
            'orders' => $orders,
            'orderStatusCounts' => $orderStatusCounts,
            'filterStatus' => $filterStatus,
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData(),
            'bodyClass' => 'bg-gray-50'
        ];
    }

    public function getShopDetailData(int $shopId): ?array {
        $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get shop information
        $shopSql = "SELECT 
                        s.*,
                        COUNT(DISTINCT p.id) as products_count,
                        COUNT(DISTINCT o.id) as orders_count,
                        AVG(r.rating) as rating
                    FROM shops s
                    LEFT JOIN products p ON s.id = p.shop_id AND p.status = 'active'
                    LEFT JOIN orders o ON s.id = o.shop_id AND o.status IN ('Completed', 'Shipping', 'Processing')
                    LEFT JOIN reviews r ON p.id = r.product_id
                    WHERE s.id = :shop_id
                    GROUP BY s.id";

        $stmt = $pdo->prepare($shopSql);
        $stmt->execute([':shop_id' => $shopId]);
        $shop = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$shop) {
            return null;
        }

        // Add default values
        $shop['rating'] = $shop['rating'] ? round($shop['rating'], 1) : 0;

        // Get shop products
        $productsSql = "SELECT 
                            p.*,
                            AVG(r.rating) as rating,
                            (SELECT filename FROM product_images WHERE product_id = p.id ORDER BY id ASC LIMIT 1) as image
                        FROM products p
                        LEFT JOIN reviews r ON p.id = r.product_id
                        WHERE p.shop_id = :shop_id AND p.status = 'active'
                        GROUP BY p.id
                        ORDER BY p.sold DESC, p.created_at DESC";

        $productsStmt = $pdo->prepare($productsSql);
        $productsStmt->execute([':shop_id' => $shopId]);
        $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

        // Process product images
        foreach ($products as &$product) {
            if ($product['image']) {
                $product['image'] = '/SHooad/public/assets/products/' . $product['image'];
            } else {
                $product['image'] = '/SHooad/public/assets/products/default.jpg';
            }
            $product['rating'] = $product['rating'] ? round($product['rating'], 1) : 0;
        }

        return [
            'shop' => $shop,
            'products' => $products,
            'navigation' => $this->getNavigationData(),
            'header' => $this->getHeaderData(),
            'bodyClass' => 'bg-gray-50'
        ];
    }
}
