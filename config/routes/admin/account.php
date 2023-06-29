<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use App\Admin\Controller\AccountController;
use App\Admin\Controller\LoginController;
use App\Admin\Middleware\HttpJwtMiddleware;
use Hyperf\HttpServer\Router\Router;
use Hyperf\Validation\Middleware\ValidationMiddleware;

// 账号登陆
Router::post('/login', [LoginController::class, 'login'], ['middleware' => [ValidationMiddleware::class]]);
// 账号登出
Router::post('/logout', [LoginController::class, 'logout'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 获取账号信息
Router::get('/info', [AccountController::class, 'info'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
