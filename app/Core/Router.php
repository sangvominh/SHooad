<?php
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $uri);  // ví dụ: ['SHooad', 'public', 'seller', 'customer']

$module = $parts[2] ?? ''; // seller / customer / admin

switch ($module) {
    case '':
        // Redirect to customer home
        require_once __DIR__ . '/../Controllers/UserController.php';
        $controller = new UserController();
        $controller->home();
        break;
    case 'seller':
        require_once __DIR__ . '/../Routes/seller.php';
        break;
    case 'customer':
        require_once __DIR__ . '/../Routes/customer.php';
        break;
    case 'user': // Support legacy 'user' for backward compatibility
        require_once __DIR__ . '/../Routes/customer.php';
        break;
    default:
        echo "404 - Page not found";
        break;
}
