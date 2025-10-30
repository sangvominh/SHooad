<?php
// public/index.php - Application entry point

declare(strict_types=1);

// Start secure session
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true,
]);

// Simple autoloader for app namespace (basic PSR-4 like)
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../app/';
    $paths = [
        'Controllers/' . $class . '.php',
        'Models/' . $class . '.php',
        'Core/' . $class . '.php',
        'Helpers/' . $class . '.php',
    ];
    foreach ($paths as $relative) {
        $file = $baseDir . $relative;
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Bootstrap Router
try {
    // Security headers
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");

    $router = new Router();

    // Default routes (can be extended in Core/Router.php)
    $router->get('/seller/dashboard', 'SellerDashboardController@index');
    $router->post('/seller/dashboard/products/{id}/pause', 'SellerDashboardController@pauseProduct');
    $router->post('/seller/dashboard/products/{id}/delete', 'SellerDashboardController@deleteProduct');

    // Dispatch the current request
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    error_log('Bootstrap error: ' . $e->getMessage());
    http_response_code(500);
    echo '<h1>Internal Server Error</h1><p>Please try again later.</p>';
}
