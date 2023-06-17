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
use Carbon\Carbon;
use Core\Constants\Enum;
use Core\Constants\ErrorCode;
use Core\Helper\JWTHelper;
use Core\Helper\RequestHelper;
use function Hyperf\Support\make;

class LoginLogic extends Logic
{
    /**
     * 账号登录.
     * @param string $username 登录账号
     * @param string $password 登录密码
     * @return array
     */
    public function login(string $username, string $password): array
    {
        $account = Account::getFindByUsername($username);
        if (empty($account)) {
            return error(ErrorCode::ACCOUNT_NOT_EXIST_ERROR);
        }
        if (Account::passwordVerify($password, $account->getAttributeValue('password')) === false) {
            return error(ErrorCode::ACCOUNT_OR_PASSWORD_ERROR);
        }
        if ($account->getAttributeValue('status') != Enum::STATUS_ON) {
            return error(ErrorCode::ACCOUNT_FROZEN_ERROR);
        }

        // 更新登录信息
        $now = Carbon::now();
        $token = make(JWTHelper::class)->getToken($account->getAttributeValue('id'));
        $account->setAttribute('current_login_token', $token);
        $account->setAttribute('last_login_ip', RequestHelper::getClientIP());
        $account->setAttribute('last_login_at', $now);
        empty($account->getAttributeValue('first_login_at')) && $account->setAttribute('first_login_at', $now);
        if (! $account->save()) {
            return error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
        }

        return [$token, $account];
    }

    /**
     * 账号登出.
     * @param Account $account
     * @return Account
     */
    public function logout(Account $account): Account
    {
        $account->setAttribute('current_login_token', '');
        if (! $account->save()) {
            return error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
        }

        return $account;
    }
}
