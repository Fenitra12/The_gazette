<?php

// Autoloader PSR-4 natif
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
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

// Helpers pour le redimensionnement d'images
function resized(string $src, int $w, int $h = 0): string {
    return \App\Core\ImageHelper::url($src, $w, $h);
}

function img(string $src, string $alt, int $w, int $h = 0, bool $lazy = true): string {
    return \App\Core\ImageHelper::tag($src, $alt, $w, $h, $lazy);
}

// Config DB
$config = require __DIR__ . '/../config/database.php';
\App\Core\Database::init($config);

// Routes
$router = new \App\Core\Router();

$router->add('GET', '/',                        'HomeController@index');
$router->add('GET', '/article/{slug}',          'ArticleController@show');
$router->add('GET', '/categorie/{slug}',        'CategoryController@show');
$router->add('GET', '/categorie/{slug}/{page}', 'CategoryController@show');
$router->add('GET', '/about',                   'PageController@about');
$router->add('GET', '/contact',                 'PageController@contact');
$router->add('GET', '/sitemap.xml',             'SitemapController@index');

// Dispatch
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$router->dispatch($method, $uri);
