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
use App\Knowledge\Controller\ArticleCategoryController;

// 文章模块
Router::addGroup('/article', function () {
    // 获取文章列表
    Router::get('/list', [ArticleController::class, 'list']);
    // 新增文章
    Router::post('/add', [ArticleController::class, 'add']);
    // 获取文章信息
    Router::get('/info/{id}', [ArticleController::class, 'info']);
    // 更新文章
    Router::put('/update/{id}', [ArticleController::class, 'update']);
    // 修改文章属性
    Router::patch('/update/{id}/{field}', [ArticleController::class, 'updateField']);
    // 删除文章
    Router::delete('/delete/{id}', [ArticleController::class, 'delete']);

    // 分类模块
    Router::addGroup('/category', function () {
        // 获取分类列表
        Router::get('/list', [ArticleCategoryController::class, 'list']);
        // 新增分类
        Router::post('/add', [ArticleCategoryController::class, 'add']);
        // 获取分类信息
        Router::get('/info/{id}', [ArticleCategoryController::class, 'info']);
        // 更新分类
        Router::put('/update/{id}', [ArticleCategoryController::class, 'update']);
        // 修改分类属性
        Router::patch('/update/{id}/{field}', [ArticleCategoryController::class, 'updateField']);
        // 删除分类
        Router::delete('/delete/{id}', [ArticleCategoryController::class, 'delete']);
    });
});
