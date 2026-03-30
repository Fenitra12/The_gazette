<?php
declare(strict_types=1);

use BackOffice\Controllers\AuthController;
use BackOffice\Controllers\DashboardController;
use BackOffice\Controllers\ArticleController;
use BackOffice\Controllers\CategoryController;
use BackOffice\Controllers\AuthorController;
use BackOffice\Controllers\UserController;

// Auth
$router->add('GET', '/login', [AuthController::class, 'showLogin']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('POST', '/logout', [AuthController::class, 'logout']);

// Dashboard (protected)
$router->add('GET', '/', [DashboardController::class, 'index'], ['auth' => true]);

// Users (protected)
$router->add('GET', '/users', [UserController::class, 'index'], ['auth' => true]);
$router->add('GET', '/users/create', [UserController::class, 'create'], ['auth' => true]);
$router->add('POST', '/users', [UserController::class, 'store'], ['auth' => true]);
$router->add('GET', '/users/{id}/edit', [UserController::class, 'edit'], ['auth' => true]);
$router->add('POST', '/users/{id}', [UserController::class, 'update'], ['auth' => true]);
$router->add('POST', '/users/{id}/delete', [UserController::class, 'destroy'], ['auth' => true]);

// Categories (protected)
$router->add('GET', '/categories', [CategoryController::class, 'index'], ['auth' => true]);
$router->add('GET', '/categories/create', [CategoryController::class, 'create'], ['auth' => true]);
$router->add('POST', '/categories', [CategoryController::class, 'store'], ['auth' => true]);
$router->add('GET', '/categories/{id}/edit', [CategoryController::class, 'edit'], ['auth' => true]);
$router->add('POST', '/categories/{id}', [CategoryController::class, 'update'], ['auth' => true]);
$router->add('POST', '/categories/{id}/delete', [CategoryController::class, 'destroy'], ['auth' => true]);

// Authors (protected)
$router->add('GET', '/authors', [AuthorController::class, 'index'], ['auth' => true]);
$router->add('GET', '/authors/create', [AuthorController::class, 'create'], ['auth' => true]);
$router->add('POST', '/authors', [AuthorController::class, 'store'], ['auth' => true]);
$router->add('GET', '/authors/{id}/edit', [AuthorController::class, 'edit'], ['auth' => true]);
$router->add('POST', '/authors/{id}', [AuthorController::class, 'update'], ['auth' => true]);
$router->add('POST', '/authors/{id}/delete', [AuthorController::class, 'destroy'], ['auth' => true]);

// Articles CRUD (protected)
$router->add('GET', '/articles', [ArticleController::class, 'index'], ['auth' => true]);
$router->add('GET', '/articles/create', [ArticleController::class, 'create'], ['auth' => true]);
$router->add('POST', '/articles', [ArticleController::class, 'store'], ['auth' => true]);
$router->add('GET', '/articles/{id}', [ArticleController::class, 'show'], ['auth' => true]);
$router->add('GET', '/articles/{id}/edit', [ArticleController::class, 'edit'], ['auth' => true]);
$router->add('POST', '/articles/{id}', [ArticleController::class, 'update'], ['auth' => true]);
$router->add('POST', '/articles/{id}/delete', [ArticleController::class, 'destroy'], ['auth' => true]);

