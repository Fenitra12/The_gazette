<?php
declare(strict_types=1);

use BackOffice\Core\App;
use BackOffice\Core\Database;

ini_set('display_errors', '0');
error_reporting(E_ALL);

App::init();
Database::init([
    'driver' => getenv('DB_DRIVER') ?: 'pgsql',
    'host' => getenv('DB_HOST') ?: 'mvc_db',
    'port' => getenv('DB_PORT') ?: '5432',
    'database' => getenv('DB_NAME') ?: 'thegazette',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: 'password',
]);

