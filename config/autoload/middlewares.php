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
use Core\Middleware\HttpJwtMiddleware;
use Hyperf\Validation\Middleware\ValidationMiddleware;

return [
    // 数组内配置您的全局中间件，顺序根据该数组的顺序
    'http' => [
        HttpJwtMiddleware::class,
        ValidationMiddleware::class,
    ],
    'websocket' => [],
];
