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
    Router::get('/list', [ArticleController::class, 'list']);
});
