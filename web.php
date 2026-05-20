<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;
use App\Middleware\CsrfMiddleware;
use App\Middleware\AuthMiddleware;

$app->get('/', function($request) {
    return App\Core\View::render('auth/login', ['title' => 'AstraVPN Login']);
});

$app->post('/login', [AuthController::class, 'login'], [CsrfMiddleware::class]);
$app->post('/register', [AuthController::class, 'register'], [CsrfMiddleware::class]);
$app->get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);
$app->post('/logout', [AuthController::class, 'logout'], [AuthMiddleware::class, CsrfMiddleware::class]);

$app->get('/admin', [AdminController::class, 'index'], [AuthMiddleware::class]);
$app->get('/admin/users', [AdminController::class, 'users'], [AuthMiddleware::class]);
$app->get('/admin/servers', [AdminController::class, 'servers'], [AuthMiddleware::class]);
