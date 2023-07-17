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
use App\Admin\Controller\ArticleCategoryController;
use App\Admin\Controller\ArticleController;
use App\Admin\Middleware\HttpJwtMiddleware;
use Hyperf\HttpServer\Router\Router;
use Hyperf\Validation\Middleware\ValidationMiddleware;

// 获取文章列表
Router::get('/list', [ArticleController::class, 'list'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 新增文章
Router::post('/add', [ArticleController::class, 'add'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 获取文章信息
Router::get('/info/{id}', [ArticleController::class, 'info'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 更新文章
Router::put('/update/{id}', [ArticleController::class, 'update'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 修改文章属性
Router::patch('/update/{id}/{field}', [ArticleController::class, 'updateField'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
// 删除文章
Router::delete('/delete/{id}', [ArticleController::class, 'delete'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);

// 分类模块
Router::addGroup('/category', function () {
    // 获取分类列表
    Router::get('/list', [ArticleCategoryController::class, 'list'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
    // 获取分类选择列表 (比如新增文章时)
    Router::get('/list/select', [ArticleCategoryController::class, 'listSelect'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
    // 新增分类
    Router::post('/add', [ArticleCategoryController::class, 'add'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
    // 获取分类信息
    Router::get('/info/{id}', [ArticleCategoryController::class, 'info'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
    // 更新分类
    Router::put('/update/{id}', [ArticleCategoryController::class, 'update'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
    // 修改分类属性
    Router::patch('/update/{id}/{field}', [ArticleCategoryController::class, 'updateField'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
    // 删除分类
    Router::delete('/delete/{id}', [ArticleCategoryController::class, 'delete'], ['middleware' => [HttpJwtMiddleware::class, ValidationMiddleware::class]]);
});
