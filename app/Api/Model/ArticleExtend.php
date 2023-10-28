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

/**
 * @property int $id 主键
 * @property int $article_id 文章ID
 * @property string $source 文章来源
 * @property string $source_url 文章来源链接
 * @property string $content 文章正文
 * @property string $keyword 文章关键词
 * @property string $attachment_path 文章附件路径
 */
class ArticleExtend extends Model
{
    public bool $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'article_extend';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'article_id', 'source', 'source_url', 'content', 'keyword', 'attachment_path'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'article_id' => 'integer'];
}
