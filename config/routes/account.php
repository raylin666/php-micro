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
use Hyperf\HttpServer\Router\Router;
use App\Account\Controller\LoginController;
use App\Account\Controller\AccountController;

// 账号登陆
Router::post('/login', [LoginController::class, 'login']);
// 账号登出
Router::post('/logout', [LoginController::class, 'logout']);

// 获取账号信息
Router::get('/info', [AccountController::class, 'info']);
