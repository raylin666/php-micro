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
namespace App\Api\Logic;

use App\Api\Model\Article;
use Core\Constants\ErrorCode;
use Hyperf\Contract\LengthAwarePaginatorInterface;

class ArticleLogic extends Logic
{
    /**
     * 获取文章列表.
     * @param int $page 页码
     * @param int $size 数量
     * @return LengthAwarePaginatorInterface 分页器
     */
    public function list(int $page, int $size): LengthAwarePaginatorInterface
    {
        if (($page < 1) || ($size < 1)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        return Article::getPageList($page, $size);
    }

    /**
     * 获取文章信息.
     * @param int $id 文章ID
     */
    public function info(int $id): array
    {
        if ($id < 1) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $result = Article::getInfoById($id);
        if (empty($result)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        return [
            'id' => $result['id'],
            'title' => $result['title'],
            'author' => $result['author'],
            'summary' => $result['summary'],
            'cover' => $result['cover'],
            'sort' => $result['sort'],
            'recommend_flag' => $result['recommend_flag'],
            'commented_flag' => $result['commented_flag'],
            'status' => $result['status'],
            'view_count' => $result['view_count'],
            'comment_count' => $result['comment_count'],
            'collection_count' => $result['collection_count'],
            'zan_count' => $result['zan_count'],
            'share_count' => $result['share_count'],
            'user_id' => $result['user_id'],
            'last_commented_at' => $result['last_commented_at'],
            'created_at' => $result['created_at'],
            'updated_at' => $result['updated_at'],
            'source' => $result['source'],
            'source_url' => $result['source_url'],
            'content' => $result['content'],
            'keyword' => $result['keyword'],
            'category' => $result['category'],
            'attachment_path' => $result['attachment_path'],
        ];
    }
}
