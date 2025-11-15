<?php
require_once __DIR__ . '/../Controllers/UserController.php';
$user_controller = new UserController();

$path = $parts[3] ?? '';

switch ($path) {
    case '':
        $user_controller->home();
        break;

    case 'register':
        $user_controller->register();
        break;

    case 'login':
        $user_controller->login();
        break;

    case 'logout':
        $user_controller->logout();
        break;

    case 'cart':
        $user_controller->cart();
        break;

    case 'products':
        $user_controller->products();
        break;

    case 'product-detail':
        $user_controller->productDetail();
        break;

    default:
        $user_controller->home();
        break;
}
?>