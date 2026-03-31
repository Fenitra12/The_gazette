<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $path, $handler)
    {
        // Convertir {param} en regex nommée
        $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch($method, $uri)
    {
        $method = strtoupper($method);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $uri, $matches)) {
                [$controller, $action] = explode('@', $route['handler']);
                $controllerClass = "App\\Controllers\\" . $controller;

                if (!class_exists($controllerClass)) {
                    continue;
                }

                $instance = new $controllerClass();
                if (!method_exists($instance, $action)) {
                    continue;
                }

                // Extraire les paramètres nommés
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array([$instance, $action], $params);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
