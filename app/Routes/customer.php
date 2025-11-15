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
        // TODO: Implement checkout
        $customerController->home();
        break;

    case 'profile':
        // TODO: Implement profile
        $customerController->home();
        break;

    default:
        $customerController->home();
        break;
}
?>