<?php
namespace App\Core;

class Router {
    public static function route() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        // dirname may return '/' for root — normalize it
        $scriptDir = rtrim(dirname($scriptName), '/'); // e.g. '/SHooad/public' or ''

        if ($scriptDir !== '' && $scriptDir !== '/') {
            // remove the script directory prefix from the URI
            $path = substr($uri, strlen($scriptDir));
        } else {
            $path = $uri;
        }

        $path = trim($path, '/'); // e.g. "user/register"

        switch ($path) {
            case '':
                require_once __DIR__ . '/../Views/home.php';
                // stop here for the root path so it doesn't fall through to 'seller'
                break;
            case 'seller':
                require_once __DIR__ . '/../Controllers/SellerController.php';
                $controller = new \App\Controllers\SellerController();
                $controller->dashboard();
                break;

            case 'user/register':
                require_once __DIR__ . '/../Controllers/UserController.php';
                $controller = new \App\Controllers\UserController();
                $controller->register();
                break;

            case 'user/login':
                require_once __DIR__ . '/../Controllers/UserController.php';
                $controller = new \App\Controllers\UserController();
                $controller->login();
                break;

            case 'user/logout':
                require_once __DIR__ . '/../Controllers/UserController.php';
                $controller = new \App\Controllers\UserController();
                $controller->logout();
                break;

            // case '':
            //     // Handle root path
            //     header('Location: /user/login');
            //     exit;

            default:
                http_response_code(404);
                $notFound = __DIR__ . '/../Views/404.php';
                if (file_exists($notFound)) {
                    include $notFound;
                } else {
                    // fallback message when Views/404.php doesn't exist
                    echo "<h1>404 Not Found</h1><p>Không tìm thấy trang: /" . htmlspecialchars($path) . "</p>";
                }
                break;
        }
    }
}
?>
