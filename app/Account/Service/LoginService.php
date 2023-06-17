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
namespace App\Account\Service;

use App\Account\Logic\LoginLogic;
use App\Account\Model\Account;
use Core\Constants\ErrorCode;
use Hyperf\Di\Annotation\Inject;

class LoginService extends Service
{
    #[Inject]
    public LoginLogic $loginLogic;

    /**
     * 账号登录.
     * @param string $username
     * @param string $password
     * @return array
     */
    public function login(string $username, string $password): array
    {
        if (empty($username) || empty($password)) {
            return error(ErrorCode::ACCOUNT_OR_PASSWORD_ERROR);
        }

        /** @var Account $account */
        [$token, $account] = $this->loginLogic->login($username, $password);
        if (empty($account)) {
            return error(ErrorCode::LOGIN_ERROR);
        }

        $account = Account::getInfo($account);
        $account['token'] = $token;
        return $account;
    }

    /**
     * 账号登出.
     * @return array
     */
    public function logout(): array
    {
        $account = $this->getContextAccount();
        if (empty($account)) {
            return error(ErrorCode::INVALID_IDENTITY_ERROR);
        }

        $account = $this->loginLogic->logout($account);

        return [
            'id' => $account->getAttributeValue('id'),
        ];
    }
}
