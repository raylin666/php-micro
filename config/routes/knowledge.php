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
use App\Knowledge\Controller\ArticleController;

// 文章模块
Router::addGroup('/article', function () {
    // 获取文章列表
    Router::get('/list', [ArticleController::class, 'list']);
    // 新增文章
    Router::post('/add', [ArticleController::class, 'add']);
    // 更新文章
    Router::put('/update/{id}', [ArticleController::class, 'update']);
    // 修改文章属性
    Router::patch('/update/{id}/{field}', [ArticleController::class, 'updateField']);
    // 删除文章
    Router::delete('/delete/{id}', [ArticleController::class, 'delete']);
});
