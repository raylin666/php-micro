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
namespace App\Websocket\Logic;

use App\Admin\Model\Account;
use Core\Constants\ErrorCode;
use Core\Exception\JWTException;
use Core\Exception\TokenValidException;
use Core\Helper\JWTHelper;
use function Hyperf\Support\make;

class AdminLogic extends Logic
{
    /**
     * JWT 中间件验证.
     * @param string $authorization Header Authorization 校验
     * @return array
     */
    public function JWTMiddleware(string $authorization): array
    {
        $jwt = make(JWTHelper::class);

        if (
            empty($authorization)
            || ! preg_match('/Bearer\\s(\\S+)/', $authorization, $matches)
        ) {
            throw new JWTException(t('jwt.not_token'));
        }

        $authorization = $matches[1];
        $token = $jwt->checkToken($authorization);
        if ($token === false) {
            throw new TokenValidException(t('jwt.token_valid'));
        }

        // 获取账号ID
        $accountId = intval($jwt->getTokenClaims($token)->get('jti'));
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

        return [];
    }
}
