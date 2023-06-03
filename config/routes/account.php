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

const ACCOUNT_MODULE_CONTROLLER = '\App\Account\Controller';

// 账号登陆
Router::post('/login', ACCOUNT_MODULE_CONTROLLER . '\\AccountController@index');
