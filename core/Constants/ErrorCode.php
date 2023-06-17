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
namespace Core\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * 业务状态码
 *
 * #### 错误码为 6 位数
 *
 * | 1 | 00 | 001 |
 * | :------ | :------ | :------ |
 * | 服务级错误码 | 模块级错误码 | 具体错误码 |
 *
 * - 服务级错误码：1 位数进行表示，比如 1 为系统级错误；2 为普通错误，通常是由用户非法操作引起。
 * - 模块级错误码：2 位数进行表示，比如 00 为系统相关; 01 为字典模块；02 为用户模块 ...。
 * - 具体的错误码：3 位数进行表示，比如 001 为手机号不合法；002 为验证码输入错误。
 *
 * @method static getMessage($code)
 * @Constants
 */
#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * 系统相关错误状态码 00.
     */
    /**
     * @Message("系统令牌错误")
     */
    public const SYSTEM_JWT_ALGORITHM_NOT_METHOD_ERROR = 100001;
    /**
     * @Message("新增数据失败")
     */
    public const SYSTEM_INSERT_DATA_ERROR = 100002;
    /**
     * @Message("更新数据失败")
     */
    public const SYSTEM_UPDATE_DATA_ERROR = 100003;
    /**
     * @Message("删除数据失败")
     */
    public const SYSTEM_DELETE_DATA_ERROR = 100004;

    /**
     * 账号相关错误状态码 01.
     */
    /**
     * @Message("无效身份")
     */
    public const INVALID_IDENTITY_ERROR = 101001;
    /**
     * @Message("登录失败, 请联系管理员")
     */
    public const LOGIN_ERROR = 101002;
    /**
     * @Message("账号或密码错误")
     */
    public const ACCOUNT_OR_PASSWORD_ERROR = 201001;
    /**
     * @Message("账号不存在")
     */
    public const ACCOUNT_NOT_EXIST_ERROR = 201002;
    /**
     * @Message("账号已冻结, 请联系管理员")
     */
    public const ACCOUNT_FROZEN_ERROR = 201003;
    /**
     * @Message("账号验证错误")
     */
    public const ACCOUNT_VALIDATE_ERROR = 201004;
    /**
     * @Message("账号已在其他区域登录, 请重新登录")
     */
    public const ACCOUNT_LOGIN_OTHER_REGION_ERROR = 201005;
}
