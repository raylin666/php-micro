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

use Hyperf\Database\Model\Collection as ModelCollection;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id 主键
 * @property string $name 分类名称
 * @property int $pid 父级分类ID
 * @property string $icon Icon 图标
 * @property string $describe 分类描述
 * @property string $question 提问格式, 二级类目才有, 替换格式类似为 %s% 用来在传递提问时的占位值
 * @property int $sort 分类排序
 * @property int $status 分类状态 0:已关闭 1:已开启
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class ChatbotCategoryScene extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'chatbot_category_scene';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'name', 'pid', 'icon', 'describe', 'question', 'sort', 'status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 获取父级列表.
     * @return ModelCollection
     */
    public static function getParentList(): ModelCollection
    {
        return self::where('pid', 0)->get();
    }

    public static function getInfoById(int $id)
    {
        return self::withTrashed()->where('id', $id)->first();
    }

    public static function hasInfoById(int $id): bool
    {
        return self::where('id', $id)->exists();
    }

    public static function hasChildById(int $id): bool
    {
        return self::where('pid', $id)->exists();
    }
}
