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
namespace App\Account\Logic;



use App\Account\Model\Account;
use Core\Constants\Enum;
use Core\Constants\ErrorCode;

class LoginLogic extends Logic
{
    public function login($username, $password)
    {
        $account = Account::getFindByUsername($username);
        if (empty($account)) {
            error(ErrorCode::ACCOUNT_NOT_EXIST_ERROR);
        }
        if (Account::passwordVerify($password, $account['password']) === false) {
            error(ErrorCode::ACCOUNT_OR_PASSWORD_ERROR);
        }
        if ($account['status'] != Enum::STATUS_ON) {
            error(ErrorCode::ACCOUNT_FROZEN_ERROR);
        }

        // 更新登录信息
        Account::updateLoginInfo($account['id']);
        var_dump($account);
        return [$username, $password];
    }
}
