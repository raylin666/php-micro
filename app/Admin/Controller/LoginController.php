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
namespace App\Admin\Controller;

use App\Admin\Request\LoginRequest;
use App\Admin\Service\LoginService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class LoginController extends Controller
{
    #[Inject]
    protected LoginService $loginService;

    /**
     * 账号登录.
     * @param LoginRequest $request
     * @return ResponseInterface
     */
    public function login(LoginRequest $request): ResponseInterface
    {
        $data = $request->validated();
        $username = strval($data['username'] ?? '');
        $password = strval($data['password'] ?? '');
        return $this->response->json($this->loginService->login($username, $password));
    }

    /**
     * 账号登出.
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        return $this->response->json($this->loginService->logout());
    }
}
