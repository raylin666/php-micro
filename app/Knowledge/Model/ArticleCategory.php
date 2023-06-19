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
namespace App\Knowledge\Model;



/**
 * @property int $id 主键
 * @property int $pid 上级分类
 * @property string $name 分类名称
 * @property string $icon 分类图标
 * @property string $color 分类颜色
 * @property int $sort 分类排序
 * @property int $status 分类状态 0:已关闭 1:已开启
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class ArticleCategory extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'article_category';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'pid', 'name', 'icon', 'color', 'sort', 'status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}