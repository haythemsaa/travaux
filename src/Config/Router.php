<?php

namespace App\Config;

class Router {
    private $routes = [];
    private $middlewares = [];

    public function get($path, $handler, $middlewares = []) {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post($path, $handler, $middlewares = []) {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    private function addRoute($method, $path, $handler, $middlewares) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path if exists
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if (strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        $requestUri = $requestUri ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertRouteToRegex($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match

                // Execute middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    if (!$middlewareInstance->handle()) {
                        return;
                    }
                }

                // Execute handler
                if (is_callable($route['handler'])) {
                    call_user_func_array($route['handler'], $matches);
                } elseif (is_array($route['handler'])) {
                    [$controller, $method] = $route['handler'];
                    $controllerInstance = new $controller();
                    call_user_func_array([$controllerInstance, $method], $matches);
                }
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
    }

    private function convertRouteToRegex($route) {
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);
        return '#^' . $route . '$#';
    }
}
