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
namespace App\Admin\Middleware;

use App\Admin\Model\Account;
use Core\Constants\ErrorCode;
use Core\Exception\JWTException;
use Core\Exception\TokenValidException;
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
            error(ErrorCode::ACCOUNT_NOT_EXIST_ERROR);
        }

        $account = Account::getFindById($accountId);
        if (empty($account)) {
            error(ErrorCode::ACCOUNT_NOT_EXIST_ERROR);
        }
        // 目前只支持单端登录, 故匹配当前登录的TOKEN和当前提交的TOKEN对比
        $currentLoginToken = $account->getAttributeValue('current_login_token');
        if (empty($currentLoginToken)) {
            error(ErrorCode::ACCOUNT_INVALID_IDENTITY_ERROR);
        }
        if ($currentLoginToken != $authorization) {
            error(ErrorCode::ACCOUNT_LOGIN_OTHER_REGION_ERROR);
        }

        // 设置上下文账号信息
        $this->withContextAccount($account);

        return $handler->handle($request);
    }
}
