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
use App\Admin\Controller\ChatBotController;
use App\Admin\Middleware\HttpJwtMiddleware;
use Hyperf\HttpServer\Router\Router;
use Hyperf\Validation\Middleware\ValidationMiddleware;

// 获取场景分类列表
Router::get('/list', [ChatBotController::class, 'list'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 获取场景分类选择列表 (比如新增分类时)
Router::get('/list/select', [ChatBotController::class, 'listSelect'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 新增场景分类
Router::post('/add', [ChatBotController::class, 'add'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 获取场景分类信息
Router::get('/info/{id}', [ChatBotController::class, 'info'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 更新场景分类
Router::put('/update/{id}', [ChatBotController::class, 'update'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 修改场景分类属性
Router::patch('/update/{id}/{field}', [ChatBotController::class, 'updateField'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 删除场景分类
Router::delete('/delete/{id}', [ChatBotController::class, 'delete'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
