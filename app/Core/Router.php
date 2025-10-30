<?php
// app/Core/Router.php

declare(strict_types=1);

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $method = strtoupper($method);

        // Try exact match first
        $normalized = $this->normalize($path);
        if (isset($this->routes[$method][$normalized])) {
            $this->invoke($this->routes[$method][$normalized], []);
            return;
        }

        // Try parameterized routes e.g., /seller/dashboard/products/{id}/pause
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            if ($pattern === null) continue;
            if (preg_match('#^' . $pattern . '$#', $normalized, $matches)) {
                array_shift($matches); // remove full match
                $this->invoke($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }

    private function invoke(string $handler, array $params): void
    {
        // Handler format: Controller@method
        if (strpos($handler, '@') === false) {
            throw new RuntimeException('Invalid route handler: ' . $handler);
        }
        [$controllerName, $method] = explode('@', $handler, 2);

        // Resolve controller
        if (!class_exists($controllerName)) {
            $path = __DIR__ . '/../Controllers/' . $controllerName . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
        if (!class_exists($controllerName)) {
            throw new RuntimeException('Controller not found: ' . $controllerName);
        }
        $controller = new $controllerName(Database::getConnection());

        if (!method_exists($controller, $method)) {
            throw new RuntimeException("Method {$method} not found in controller {$controllerName}");
        }

        // Call controller with route params
        call_user_func_array([$controller, $method], $params);
    }

    private function normalize(string $path): string
    {
        $path = rtrim($path, '/');
        return $path === '' ? '/' : $path;
    }
}
