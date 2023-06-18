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
namespace Core\Middleware;

use App\Account\Model\Account;
use Core\Constants\ErrorCode;
use Core\Exception\JWTException;
use Core\Exception\TokenValidException;
use Core\Helper\ApplicationHelper;
use Core\Helper\JWTHelper;
use Core\Traits\AccountTrait;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Throwable;

use function Hyperf\Support\make;

class HttpJwtMiddleware implements MiddlewareInterface
{
    use AccountTrait;

    protected HttpResponse $response;

    protected JWTHelper $jwt;

    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
        $this->jwt = make(JWTHelper::class);
    }

    /**
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if ($this->isIgnoreRoute($path)) {
            return $handler->handle($request);
        }

        $authorization = $request->getHeaderLine('Authorization') ?? '';
        if (
            empty($authorization)
            || ! preg_match('/Bearer\\s(\\S+)/', $authorization, $matches)
        ) {
            throw new JWTException(t('jwt.not_token'));
        }

        $authorization = $matches[1];
        $token = $this->jwt->checkToken($authorization);
        if ($token === false) {
            throw new TokenValidException(t('jwt.token_valid'));
        }

        // 获取账号ID
        $accountId = intval($this->jwt->getTokenClaims($token)->get('jti'));
        if ($accountId <= 0) {
            return error(ErrorCode::ACCOUNT_NOT_EXIST_ERROR);
        }

        $account = Account::getFindById($accountId);
        if (empty($account)) {
            return error(ErrorCode::ACCOUNT_NOT_EXIST_ERROR);
        }
        // 目前只支持单端登录, 故匹配当前登录的TOKEN和当前提交的TOKEN对比
        $currentLoginToken = $account->getAttributeValue('current_login_token');
        if (empty($currentLoginToken)) {
            return error(ErrorCode::INVALID_IDENTITY_ERROR);
        }
        if ($currentLoginToken != $authorization) {
            return error(ErrorCode::ACCOUNT_LOGIN_OTHER_REGION_ERROR);
        }

        // 设置上下文账号信息
        $this->withContextAccount($account);

        return $handler->handle($request);
    }

    protected function isIgnoreRoute(string $path): bool
    {
        $path = rtrim($path, '/');
        $ignoreRoute = ApplicationHelper::getConfig()->get('jwt.ignore_route', []);
        if (($path == '/favicon.ico') || ($path == '/heartbeat')) {
            return true;
        }

        foreach ($ignoreRoute as $route) {
            if (str_ends_with($route, '*')) {
                if (str_starts_with($path, rtrim($route, '*'))) {
                    return true;
                }
            } else {
                if ($route == $path) {
                    return true;
                }
            }
        }

        return false;
    }
}
