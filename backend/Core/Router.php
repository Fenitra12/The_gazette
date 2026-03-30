<?php
declare(strict_types=1);

namespace BackOffice\Core;

final class Router
{
    /** @var array<int, array{method:string,pattern:string,regex:string,vars:array<int,string>,handler:callable|array,options:array<string,mixed>}> */
    private array $routes = [];

    /**
     * @param callable|array{0:class-string,1:string} $handler
     * @param array<string,mixed> $options
     */
    public function add(string $method, string $pattern, callable|array $handler, array $options = []): void
    {
        $vars = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function (array $m) use (&$vars): string {
            $vars[] = $m[1];
            return '(?P<' . $m[1] . '>\d+)';
        }, $pattern) ?? $pattern;

        $regex = '#^' . $regex . '$#';

        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'regex' => $regex,
            'vars' => $vars,
            'handler' => $handler,
            'options' => $options,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['regex'], $uri, $matches)) {
                continue;
            }

            if (!empty($route['options']['auth']) && !Auth::check()) {
                Response::redirect('/login');
                return;
            }

            $params = [];
            foreach ($route['vars'] as $v) {
                if (isset($matches[$v])) {
                    $params[$v] = (int)$matches[$v];
                }
            }

            $handler = $route['handler'];
            if (is_array($handler)) {
                [$class, $action] = $handler;
                $controller = new $class();
                $controller->$action($params);
                return;
            }

            $handler($params);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}

