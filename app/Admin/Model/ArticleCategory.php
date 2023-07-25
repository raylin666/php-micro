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

use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Collection as ModelCollection;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id 主键
 * @property int $pid 上级分类
 * @property string $name 分类名称
 * @property string $cover 分类封面
 * @property int $sort 分类排序
 * @property int $status 分类状态 0:已关闭 1:已开启
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class ArticleCategory extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'article_category';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'pid', 'name', 'cover', 'sort', 'status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 获取父级列表.
     */
    public static function getParentList(): ModelCollection
    {
        return self::where('pid', 0)->orderByDesc('sort')->get();
    }

    public static function getInfoById(int $id)
    {
        return self::withTrashed()->where('id', $id)->first();
    }

    public static function hasInfoById(int $id, $status = null): bool
    {
        $builder = self::where('id', $id);
        if (! is_null($status)) {
            $builder->where('status', $status);
        }

        return $builder->exists();
    }

    public static function hasChildById(int $id): bool
    {
        return self::where('pid', $id)->exists();
    }

    public static function getCategoryByArticleIds(array $ids, array $columns = ['*']): Collection
    {
        return self::whereIn('id', $ids)->select($columns)->get();
    }

    public static function getArticleCountById(int $id): int
    {
        return ArticleCategoryRelation::where('category_id', $id)->count();
    }

    public static function getArticleCountsByIds(array $ids): Collection
    {
        return ArticleCategoryRelation::whereIn('category_id', $ids)
            ->selectRaw('category_id, count(1) as count')
            ->groupBy(['category_id'])
            ->get();
    }
}
