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
namespace App\Knowledge\Logic;

use App\Knowledge\Model\Article;
use App\Knowledge\Model\ArticleExtend;
use Carbon\Carbon;
use Core\Constants\ErrorCode;
use Core\Exception\BusinessException;
use Exception;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\DbConnection\Db;

use function Hyperf\Support\make;

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
     * 新增文章.
     * @param array $data 数据内容
     */
    public function add(array $data): int
    {
        $title = $data['title'] ?? '';
        $summary = $data['summary'] ?? '';
        $cover = $data['cover'] ?? '';
        $sort = intval($data['sort'] ?? 0);
        $recommendFlag = intval($data['recommend_flag'] ?? 0);
        $commentedFlag = intval($data['commented_flag'] ?? 0);
        $status = intval($data['status'] ?? 0);
        $userId = intval($data['user_id'] ?? 0);
        $source = $data['source'] ?? '';
        $sourceUrl = $data['source_url'] ?? '';
        $content = $data['content'] ?? '';
        $keyword = $data['keyword'] ?? '';
        $attachmentPath = $data['attachment_path'] ?? '';

        if (empty($title)
            || empty($summary)
            || empty($cover)
            || empty($userId)
            || empty($content)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        Db::beginTransaction();

        try {
            $article = make(Article::class);
            $article->setAttribute('title', $title);
            $article->setAttribute('summary', $summary);
            $article->setAttribute('cover', $cover);
            $article->setAttribute('sort', $sort);
            $article->setAttribute('recommend_flag', $recommendFlag);
            $article->setAttribute('commented_flag', $commentedFlag);
            $article->setAttribute('status', $status);
            $article->setAttribute('user_id', $userId);
            if (! $article->save()) {
                error(ErrorCode::SYSTEM_INSERT_DATA_ERROR);
            }

            $id = $article->getAttributeValue('id');

            $articleExtend = make(ArticleExtend::class);
            $articleExtend->setAttribute('article_id', $id);
            $articleExtend->setAttribute('source', $source);
            $articleExtend->setAttribute('source_url', $sourceUrl);
            $articleExtend->setAttribute('content', $content);
            $articleExtend->setAttribute('keyword', $keyword);
            $articleExtend->setAttribute('attachment_path', $attachmentPath);
            if (! $articleExtend->save()) {
                error(ErrorCode::SYSTEM_INSERT_DATA_ERROR);
            }

            Db::commit();
        } catch (Exception|BusinessException $exception) {
            Db::rollBack();

            if ($exception instanceof BusinessException) {
                error($exception->getCode());
            }

            error(ErrorCode::SYSTEM_UNKNOWN_ERROR);
        }

        return $id;
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

        $result = Article::getInfo($id);
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
            'attachment_path' => $result['attachment_path'],
        ];
    }

    /**
     * 更新文章.
     * @param int $id 文章ID
     * @param array $data 更新内容
     */
    public function update(int $id, array $data): int
    {
        if (! Article::hasInfo($id)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $title = $data['title'] ?? '';
        $summary = $data['summary'] ?? '';
        $cover = $data['cover'] ?? '';
        $userId = intval($data['user_id'] ?? 0);
        $content = $data['content'] ?? '';

        if (empty($title)
            || empty($summary)
            || empty($cover)
            || empty($userId)
            || empty($content)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        Db::beginTransaction();

        try {
            $article = Article::find($id);
            $article->setAttribute('title', $title);
            $article->setAttribute('summary', $summary);
            $article->setAttribute('cover', $cover);
            $article->setAttribute('user_id', $userId);
            $article->setAttribute('updated_at', Carbon::now());
            (isset($data['sort']) && is_numeric($data['sort'])) && $article->setAttribute('sort', $data['sort']);
            (isset($data['recommend_flag']) && is_numeric($data['recommend_flag'])) && $article->setAttribute('recommend_flag', $data['recommend_flag']);
            (isset($data['commented_flag']) && is_numeric($data['commented_flag'])) && $article->setAttribute('commented_flag', $data['commented_flag']);
            (isset($data['status']) && is_numeric($data['status'])) && $article->setAttribute('status', $data['status']);
            if (! $article->save()) {
                error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
            }

            $articleExtend = ArticleExtend::getByArticleId($id);
            $articleExtend->setAttribute('content', $content);
            (isset($data['source']) && $data['source']) && $articleExtend->setAttribute('source', $data['source']);
            (isset($data['source_url']) && $data['source_url']) && $articleExtend->setAttribute('source_url', $data['source_url']);
            (isset($data['keyword']) && $data['keyword']) && $articleExtend->setAttribute('keyword', $data['keyword']);
            (isset($data['attachment_path']) && $data['attachment_path']) && $articleExtend->setAttribute('attachment_path', $data['attachment_path']);
            if (! $articleExtend->save()) {
                error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
            }

            Db::commit();
        } catch (Exception|BusinessException $exception) {
            Db::rollBack();

            if ($exception instanceof BusinessException) {
                error($exception->getCode());
            }

            error(ErrorCode::SYSTEM_UNKNOWN_ERROR);
        }

        return $id;
    }

    /**
     * 修改文章属性.
     * @param int $id 文章ID
     * @param string $field 属性字段
     * @param string $value 属性值
     */
    public function updateField(int $id, string $field, string $value): Article
    {
        $value = trim($value);
        if ($value === '') {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        if (! Article::hasInfo($id)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $article = Article::find($id);
        // 过滤暂时不支持的字段
        switch ($field) {
            case 'recommend_flag':
            case 'commented_flag':
            case 'status':
                $value = (($value == 'true') || (intval($value) > 0)) ? 1 : 0;
                break;
            case 'sort':
                $value = intval($value);
                break;
            default:
                error(ErrorCode::SYSTEM_INVALID_INSTRUCTION_ERROR);
        }

        $article->setAttribute($field, $value);
        if (! $article->save()) {
            error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
        }

        return $article;
    }

    /**
     * 删除文章.
     * @param int $id 文章ID
     * @param bool $force 是否强制删除/物理删除
     * @throws Exception
     */
    public function delete(int $id, bool $force): bool
    {
        // 物理删除
        if ($force) {
            $article = Article::withTrashed()->find($id);
            if (empty($article)) {
                error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
            }

            if ($article->forceDelete()) {
                ArticleExtend::deleteByArticleId($id);
            }

            return true;
        }

        // 软删除
        $article = Article::find($id);
        if (empty($article)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $article->delete();
        return true;
    }
}
