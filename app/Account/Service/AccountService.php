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
use App\Account\Logic\AccountLogic;
use App\Account\Model\Account;
use Core\Constants\ErrorCode;
use Hyperf\Di\Annotation\Inject;

class AccountService extends Service
{
    #[Inject]
    public AccountLogic $accountLogic;

    /**
     * 获取账号信息.
     * @return array
     */
    public function info(): array
    {
        $account = $this->getContextAccount();
        if (empty($account)) {
            return error(ErrorCode::ACCOUNT_INVALID_IDENTITY_ERROR);
        }

        return $this->accountLogic->info($account);
    }
}
