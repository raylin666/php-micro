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
namespace Core\Traits;

use App\Admin\Model\Account;
use Core\Contract\AccountInterface;
use Hyperf\Context\Context;

trait AccountTrait
{
    public function getContextAccount(): ?Account
    {
        return Context::get(AccountInterface::class);
    }

    public function withContextAccount($account): static
    {
        if ($account instanceof Account) {
            Context::set(AccountInterface::class, $account);
        }

        return $this;
    }
}
