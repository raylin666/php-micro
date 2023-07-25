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

use Core\Constants\Page;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id 主键
 * @property string $title 文章标题
 * @property string $author 文章作者
 * @property string $summary 文章摘要
 * @property string $cover 文章封面图片
 * @property int $sort 文章排序
 * @property int $recommend_flag 文章推荐标识 0:未推荐，1:已推荐
 * @property int $commented_flag 文章是否允许评论 1:允许，0:不允许
 * @property int $status 文章状态 0:已关闭 1:已开启
 * @property int $view_count 文章浏览量
 * @property int $comment_count 文章评论数
 * @property int $collection_count 文章收藏量
 * @property int $zan_count 文章点赞数
 * @property int $share_count 文章分享数
 * @property int $user_id 发布者编号ID
 * @property string $last_commented_at 最新评论时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class Article extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'article';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'title', 'author', 'summary', 'cover', 'sort', 'recommend_flag', 'commented_flag', 'status', 'view_count', 'comment_count', 'collection_count', 'zan_count', 'share_count', 'user_id', 'last_commented_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'sort' => 'integer', 'recommend_flag' => 'integer', 'commented_flag' => 'integer', 'status' => 'integer', 'view_count' => 'integer', 'comment_count' => 'integer', 'collection_count' => 'integer', 'zan_count' => 'integer', 'share_count' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function setUserIdAttribute(int $value): void
    {
        $this->attributes['user_id'] = $value;
        $this->attributes['author'] = Account::getFindById($value, ['real_username'])['real_username'] ?? '';
    }

    public static function getPageList(int $page, int $size): LengthAwarePaginatorInterface
    {
        $result = self::join('article_extend', 'article.id', '=', 'article_extend.article_id')
            ->orderByDesc('article.id')
            ->paginate(
                $size,
                [
                    'article.id', 'title', 'author', 'cover', 'sort', 'recommend_flag', 'commented_flag', 'status',
                    'view_count', 'comment_count', 'collection_count', 'zan_count', 'share_count', 'user_id',
                    'last_commented_at', 'created_at', 'updated_at', 'source', 'source_url', 'keyword',
                ],
                Page::PAGE_NAME,
                $page
            );
        foreach ($result->items() as &$item) {
            $item['keyword'] = json_decode($item['keyword'], true);
            $categoryIds = ArticleCategoryRelation::getCategoryByArticleId($item['id']);
            $item['category'] = ArticleCategory::getCategoryByArticleIds($categoryIds->toArray(), ['id', 'name']);
        }

        return $result;
    }

    /**
     * 获取文章信息 - 包含扩展表.
     * @param int $id 文章ID
     */
    public static function getInfoById(int $id): ?array
    {
        $join = self::withTrashed()
            ->join('article_extend', 'article.id', '=', 'article_extend.article_id')
            ->where('article.id', $id)
            ->select([
                'article.*', 'source', 'source_url', 'content', 'keyword', 'attachment_path',
            ]);
        $result = $join->first();
        if (empty($result)) {
            return null;
        }

        $result = $result->toArray();
        $result['keyword'] = json_decode($result['keyword'], true);
        $result['attachment_path'] = json_decode($result['attachment_path'], true);
        $categoryIds = ArticleCategoryRelation::getCategoryByArticleId($result['id']);
        $result['category'] = ArticleCategory::getCategoryByArticleIds($categoryIds->toArray());
        return $result;
    }

    public static function hasInfoById(int $id): bool
    {
        return self::join('article_extend', 'article.id', '=', 'article_extend.article_id')
            ->where('article.id', $id)
            ->exists();
    }
}
