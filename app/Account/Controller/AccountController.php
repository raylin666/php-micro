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
namespace App\Account\Controller;

use App\Account\Service\AccountService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class AccountController extends Controller
{
    #[Inject]
    protected AccountService $accountService;

    /**
     * 获取账号信息.
     * @return ResponseInterface
     */
    public function info(): ResponseInterface
    {
        return $this->response->json($this->accountService->info());
    }
}
