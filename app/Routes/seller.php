<?php
require_once __DIR__ . '/../controllers/SellerController.php';
require_once __DIR__ . '/../controllers/ImageController.php';

$seller_controller = new SellerController();
$image_controller = new ImageController();

$path = $parts[3] ?? '';

// Public routes (không cần auth)
switch ($path) {
    case 'login':
        $seller_controller->login();
        exit;
    case 'signup':
        $seller_controller->signup();
        exit;
    case 'logout':
        $seller_controller->logout();
        exit;
}

// Image serving route (requires auth)
if ($path === 'image' && isset($_GET['file'])) {
    $image_controller->serveProductImage($_GET['file']);
    exit;
}

// Protected routes (cần auth)
switch ($path) {
    case '':
    case 'dashboard':
        $seller_controller->dashboard();
        break;
        
    case 'orders':
        $seller_controller->orders();
        break;
        
    case 'order-detail':
        $seller_controller->orderDetail();
        break;
        
    case 'products':
        $seller_controller->products();
        break;
        
    case 'product-detail':
        $seller_controller->productDetail();
        break;
        
    case 'add-product':
        $seller_controller->addProduct();
        break;
        
    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        break;
}
?>