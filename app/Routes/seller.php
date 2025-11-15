<?php
require_once __DIR__ . '/../controllers/SellerController.php';
$seller_controller = new SellerController();

$path = $parts[3] ?? '';

switch ($path) {
    case (''):
    case ('dashboard'):
        $seller_controller->dashboard();
        break;
    case ('login'):
        $seller_controller->login();
        break;
    case ('signup'):
        $seller_controller->signup();
        break;
    case ('logout'):
        $seller_controller->logout();
        break;
    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
}
?>