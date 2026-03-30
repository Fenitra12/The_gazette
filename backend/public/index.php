<?php
declare(strict_types=1);

// Autoloader natif (PSR-4 simple, sans Composer)
spl_autoload_register(function (string $class): void {
    $prefix = 'BackOffice\\';
    $baseDir = __DIR__ . '/../';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

// Bootstrap
require __DIR__ . '/../config/bootstrap.php';

use BackOffice\Core\Router;

$router = new Router();
require __DIR__ . '/../config/routes.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$router->dispatch($method, $uri);

