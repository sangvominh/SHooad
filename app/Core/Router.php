<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = dirname($_SERVER['SCRIPT_NAME']); // /SHooad/public
$path = str_replace($scriptDir, '', $uri);
$path = trim($path, '/'); // "product/5"

switch ($path) {
    case ($path == 'seller'):
        require_once '../app/controllers/SellerController.php';
        break;
    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
}
?>