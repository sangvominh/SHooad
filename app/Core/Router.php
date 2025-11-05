<?php
// load controller
require_once __DIR__ . '/../controllers/SellerController.php';
$seller_controller = new SellerController();

// Get the current URI and remove the base path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/SHooad/public/';
$path = str_replace($basePath, '', $uri);
$path = trim($path, '/');

switch ($path) {
    case ('seller'):
    case ('seller/dashboard'):
        $seller_controller->dashboard();
        break;
    case ('seller/login'):
        $seller_controller->login();
        break;
    case ('seller/signup'):
        $seller_controller->signup();
        break;
    case ('seller/logout'):
        $seller_controller->logout();
        break;
    case ('seller/order-detail'):
        $seller_controller->orderDetail();
        break;
    case ('seller/update-order-status'):
        $seller_controller->updateOrderStatus();
        break;
    case (''):
        echo 'hello world';
        break;
    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
}
?>