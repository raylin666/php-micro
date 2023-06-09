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
namespace Core\Helper;

class PasswordHelper
{
    /**
     * 密码加密.
     * @param string $password 校验值
     * @param null|int|string $algo 算法类型
     * @return string 是否成功
     */
    public static function passwordHash(string $password, int|string|null $algo = PASSWORD_DEFAULT): string
    {
        return password_hash($password, $algo);
    }

    /**
     * 密码校验.
     * @param string $password 校验值
     * @param string $hash 密码值
     * @return bool 是否成功
     */
    public static function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
