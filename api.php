<?php

use App\Controllers\ApiController;
use App\Middleware\JwtMiddleware;
use App\Middleware\RateLimitMiddleware;

$app->group('/api', function($group) {
    $group->post('/auth/login', [ApiController::class, 'login']);
    $group->post('/auth/register', [ApiController::class, 'register']);
    $group->get('/profile', [ApiController::class, 'profile'], [JwtMiddleware::class]);
    $group->get('/servers', [ApiController::class, 'servers'], [JwtMiddleware::class]);
    $group->post('/configs/generate', [ApiController::class, 'generateConfig'], [JwtMiddleware::class]);
    $group->get('/devices', [ApiController::class, 'devices'], [JwtMiddleware::class]);
    $group->post('/logout', [ApiController::class, 'logout'], [JwtMiddleware::class]);
}, [RateLimitMiddleware::class]);
