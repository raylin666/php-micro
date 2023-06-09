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

use App\Account\Request\LoginRequest;
use App\Account\Service\LoginService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class LoginController extends Controller
{
    #[Inject]
    protected LoginService $service;

    /**
     * 账号登录.
     * @param LoginRequest $request
     * @return ResponseInterface
     */
    public function login(LoginRequest $request): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->service->login($data));
    }
}
