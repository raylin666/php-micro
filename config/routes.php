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
    include_once 'routes/websocket.php';
});

// 公共路由
Router::addGroup('/', function () {
    include_once 'routes/base.php';
});

// 账号相关路由
Router::addGroup('/account', function () {
    include_once 'routes/account.php';
});

// 知识库相关路由
Router::addGroup('/knowledge', function () {
    include_once 'routes/knowledge.php';
});

