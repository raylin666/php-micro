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
use App\Admin\Controller\UploadController;
use App\Admin\Middleware\HttpJwtMiddleware;
use Hyperf\HttpServer\Router\Router;
use Hyperf\Validation\Middleware\ValidationMiddleware;

// 文件资源流上传
Router::post('/file/stream', [UploadController::class, 'fileStream'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
