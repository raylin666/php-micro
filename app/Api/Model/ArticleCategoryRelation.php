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

use Hyperf\Collection\Collection;

/**
 * @property int $id 主键
 * @property int $article_id 文章ID
 * @property int $category_id 文章分类ID
 */
class ArticleCategoryRelation extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'article_category_relation';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'article_id', 'category_id'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'article_id' => 'integer', 'category_id' => 'integer'];

    public static function getCategoryByArticleId(int $articleId): Collection
    {
        return self::where('article_id', $articleId)->pluck('category_id');
    }
}
