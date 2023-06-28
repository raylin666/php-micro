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
namespace App\Admin\Logic;

use App\Admin\Model\Account;

class AccountLogic extends Logic
{
    /**
     * 获取账号信息.
     * @param Account $account
     * @return array
     */
    public function info(Account $account): array
    {
        $account = Account::getInfo($account);
        $account['role'] = 'admin';
        return $account;
    }
}
