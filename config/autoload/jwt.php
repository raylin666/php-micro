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
use Lcobucci\JWT\Encoding\JoseEncoder;

use function Hyperf\Support\env;

return [
    /*
     * 只能用于Hmac包下的加密非对称算法，其它的都会使用公私钥
     */
    'secret' => env('JWT_SECRET', 'raylin666'),

    /*
     * JWT 权限keys
     * 对称算法: HS256, HS384 & HS512 使用 `JWT_SECRET`.
     * 非对称算法: RS256, RS384 & RS512 / ES256, ES384 & ES512 使用下面的公钥私钥，需要自己去生成.
     */
    'keys' => [
        /*
         * 公钥，例如：'file:///path/to/public/key'
         */
        'public' => env('JWT_PUBLIC_KEY'),

        /*
         * 私钥，例如：'file:///path/to/private/key'
         */
        'private' => env('JWT_PRIVATE_KEY'),

        /*
         * 你的私钥的密码。不需要密码可以不用设置
         */
        'passphrase' => env('JWT_PASSPHRASE'),
    ],

    /*
     * TOKEN 开始生效时间(指多久后开始生效)，单位为秒
     */
    'not_before' => env('JWT_NOT_BEFORE', 0),

    /*
     * TOKEN 过期时间，单位为秒
     */
    'ttl' => env('JWT_TTL', 7200),

    /*
     * 支持的对称算法：HS256、HS384、HS512
     * 支持的非对称算法：RS256、RS384、RS512、ES256、ES384、ES512
     */
    'alg' => env('JWT_ALG', 'HS256'), // JWT 的 Header 加密算法

    /*
     * 签发者
     */
    'issued_by' => env('JWT_ISSUED_BY', 'raylin666'),

    /*
     * 接收者, 多个接收者用英文逗号隔开
     */
    'audience' => env('JWT_AUDIENCE', 'raylin666'),

    /*
     * 编码器
     */
    'encoder' => JoseEncoder::class,

    /*
     * 忽略路由规则
     */
    'ignore_route' => [
        '/account/login',
    ],
];
