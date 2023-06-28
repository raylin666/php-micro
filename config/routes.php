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

// WebSocket 服务
Router::addServer('websocket', function () {
    // 后台服务
    Router::addGroup('/admin', function () {
        include_once 'routes/websocket/admin.php';
    });
});

// 后台服务
Router::addGroup('/admin', function () {
    // 上传相关路由
    Router::addGroup('/upload', function () {
        include_once 'routes/admin/upload.php';
    });

    // 账号相关路由
    Router::addGroup('/account', function () {
        include_once 'routes/admin/account.php';
    });

    // 文章相关路由
    Router::addGroup('/article', function () {
        include_once 'routes/admin/article.php';
    });
});

// 前台服务
Router::addGroup('/', function () {
    // 公共路由
    include_once 'routes/api/base.php';
});
