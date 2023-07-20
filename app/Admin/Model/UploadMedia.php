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
namespace App\Admin\Model;



use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id 主键
 * @property string $hash 资源唯一哈希值
 * @property string $name 文件名称
 * @property string $domain 文件域名
 * @property string $key 文件存储路径
 * @property string $mime_type 文件类型
 * @property int $size 文件存储大小
 * @property string $ext 文件后缀
 * @property string $extra 扩展内容, JSON 格式存储
 * @property string $third_party_hash 第三方平台文件资源唯一哈希值
 * @property string $third_party_uuid 第三方平台文件资源 UUID
 * @property string $third_party 上传平台 local(本地) | qiniu(七牛云)
 * @property string $bucket 第三方平台文件资源存储仓库
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class UploadMedia extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'upload_media';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'hash', 'name', 'domain', 'key', 'mime_type', 'size', 'ext', 'extra', 'third_party_hash', 'third_party_uuid', 'third_party', 'bucket', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'size' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * @param string $domain
     * @param string $location
     * @return string
     */
    public static function getUrl(string $domain, string $location): string
    {
        return $domain . '/' . $location;
    }

    public static function getFirstByHashThirdParty(string $hash, string $thirdParty)
    {
        return self::withTrashed()->where(['hash' => $hash, 'third_party' => $thirdParty])->first();
    }
}
