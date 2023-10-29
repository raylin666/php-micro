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
namespace App\Api\Model;



use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id 主键
 * @property string $username 账号名称
 * @property string $password 账号密码
 * @property string $real_username 真实姓名
 * @property int $sex 性别 0未知 1男 2女
 * @property string $birthday_at 出生时间
 * @property string $avatar 账号头像
 * @property string $phone_area 手机区号
 * @property string $phone 手机号码
 * @property string $email 电子邮箱
 * @property string $current_login_token 当前最新登录TOKEN (可作为单点唯一登录标识)
 * @property int $last_login_ip 最后登录IP
 * @property int $status 账号状态 0关闭 1开启 2冻结
 * @property string $first_login_at 首次登录时间
 * @property string $last_login_at 最后登录时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class Account extends Model
{
    use SoftDeletes;

    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const STATUS_FREEZE = 2;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'account';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'username', 'password', 'real_username', 'sex', 'birthday_at', 'avatar', 'phone_area', 'phone', 'email', 'current_login_token', 'last_login_ip', 'status', 'first_login_at', 'last_login_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'sex' => 'integer', 'last_login_ip' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function getById(int $id, array $columns = ['*'])
    {
        return self::where(['id' => $id, 'status' => self::STATUS_ON])->select($columns)->first();
    }

    public static function getBasicById(int $id)
    {
        return self::getById($id, ['id', 'username', 'real_username', 'sex', 'avatar']);
    }
}
