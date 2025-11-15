<?php
require_once __DIR__ . '/../Controllers/UserController.php';
$customerController = new UserController();

$path = $parts[3] ?? '';

switch ($path) {
    case '':
        $customerController->home();
        break;

    case 'register':
        $customerController->register();
        break;

    case 'login':
        $customerController->login();
        break;

    case 'logout':
        $customerController->logout();
        break;

    case 'cart':
        $customerController->cart();
        break;

    case 'products':
        $customerController->products();
        break;

    case 'product-detail':
        $customerController->productDetail();
        break;

    case 'checkout':
        $customerController->checkout();
        break;
    
    case 'order-success':
        $customerController->orderSuccess();
        break;

    case 'profile':
        // TODO: Implement profile
        $customerController->home();
        break;
    
    case 'orders':
        // TODO: Implement orders list
        $customerController->home();
        break;

    default:
        $customerController->home();
        break;
}
?>