<?php
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $uri);  // ví dụ: ['SHooad', 'public', 'seller', 'update-order-status']

$module = $parts[2] ?? ''; // seller / shipper / admin / user

switch ($module) {
    case '':
        require_once __DIR__ . '/../Views/user/home.php';
        break;
    case 'seller':
        require_once __DIR__ . '/../routes/seller.php';
        break;
    case 'shipper':
        require_once __DIR__ . '/../routes/shipper.php';
        break;
    case 'admin':
        require_once __DIR__ . '/../routes/admin.php';
        break;
    case 'user':
        require_once __DIR__ . '/../routes/user.php';
        break;
    default:
        echo "404 - Page not found";
        break;
}
