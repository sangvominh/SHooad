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
        $customerController->profile();
        break;
    
    case 'update-profile':
        $customerController->updateProfile();
        break;
    
    case 'add-address':
        $customerController->addAddress();
        break;
    
    case 'change-password':
        $customerController->changePassword();
        break;
    
    case 'edit-address':
        $customerController->editAddress();
        break;
    
    case 'delete-address':
        $customerController->deleteAddress();
        break;
    
    case 'orders':
        $customerController->orders();
        break;
    
    case 'set-language':
        require_once __DIR__ . '/../Helpers/LanguageHelper.php';
        $lang = $_GET['lang'] ?? 'vi';
        LanguageHelper::setLanguage($lang);
        $redirect = $_GET['redirect'] ?? '/SHooad/public/customer';
        header('Location: ' . $redirect);
        exit;

    default:
        $customerController->home();
        break;
}
?>