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
use Core\Constants\ErrorCode;
use Core\Contract\LogicInterface;
use function Hyperf\Support\make;

class LoginService extends Service
{
    public function login(array $data)
    {
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        if (empty($username) || empty($password)) {
            return error(ErrorCode::ACCOUNT_OR_PASSWORD_ERROR);
        }

        $result = $this->getLogic()->login($username, $password);
        return null;
    }

    public function initializeLogic(): ?LogicInterface
    {
        // TODO: Implement initializeLogic() method.

        return make(LoginLogic::class);
    }
}
