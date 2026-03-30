<?php

// Autoloader natif de classes (PSR-4 basique sans composer)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Charger la configuration de la base de données
$config = require __DIR__ . '/../config/database.php';
\App\Core\Database::init($config);

// Lancer le routeur
$router = new \App\Core\Router();

// Exemple de définition des routes
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/test-db', 'HomeController@testDb');

// Dispatch de la requête actuelle
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$router->dispatch($method, $uri);
