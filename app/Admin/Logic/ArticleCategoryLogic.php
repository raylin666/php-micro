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
namespace App\Admin\Logic;

use App\Admin\Model\ArticleCategory;
use Core\Constants\ErrorCode;
use Exception;
use Hyperf\Database\Model\Collection;
use function Hyperf\Support\make;

class ArticleCategoryLogic extends Logic
{
    /**
     * 获取分类列表.
     */
    public function list(): Collection
    {
        $counts = [];
        $list = ArticleCategory::getParentList();
        if ($list->count()) {
            $counts = ArticleCategory::getArticleCountsByIds($list->pluck('id')->toArray());
            $counts = $counts->toArray();
            $counts = $counts ? array_column($counts, null, 'category_id') : [];
        }

        foreach ($list as &$item) {
            $item['article_count'] = isset($counts[$item['id']]) ? ($counts[$item['id']]['count'] ?? 0) : 0;
        }

        return $list;
    }

    /**
     * 获取分类选择列表.
     */
    public function listSelect(): Collection
    {
        return ArticleCategory::getParentList();
    }

    /**
     * 新增分类.
     * @param array $data 数据内容
     */
    public function add(array $data): int
    {
        $name = $data['name'] ?? '';
        $pid = intval($data['pid'] ?? 0);
        $cover = $data['cover'] ?? '';
        $color = $data['color'] ?? '';
        $sort = intval($data['sort'] ?? 0);
        $status = intval($data['status'] ?? 0);

        if (empty($name)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $category = make(ArticleCategory::class);
        $category->setAttribute('name', $name);
        $category->setAttribute('pid', $pid);
        $category->setAttribute('cover', $cover);
        $category->setAttribute('color', $color);
        $category->setAttribute('sort', $sort);
        $category->setAttribute('status', $status);
        if (! $category->save()) {
            error(ErrorCode::SYSTEM_INSERT_DATA_ERROR);
        }

        return $category->getAttributeValue('id');
    }

    /**
     * 获取分类信息.
     * @param int $id 分类ID
     */
    public function info(int $id): array
    {
        if ($id < 1) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $category = ArticleCategory::getInfoById($id);
        if (empty($category)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $category = $category->toArray();

        return [
            'id' => $id,
            'name' => $category['name'],
            'pid' => $category['pid'],
            'cover' => $category['cover'],
            'color' => $category['color'],
            'sort' => $category['sort'],
            'status' => $category['status'],
            'created_at' => $category['created_at'],
            'article_count' => ArticleCategory::getArticleCountById($id),
        ];
    }

    /**
     * 更新分类.
     * @param int $id 分类ID
     * @param array $data 更新内容
     */
    public function update(int $id, array $data): int
    {
        if (! ArticleCategory::hasInfoById($id)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $name = $data['name'] ?? '';
        $pid = intval($data['pid'] ?? 0);
        $cover = $data['cover'] ?? '';
        $color = $data['color'] ?? '';
        $sort = intval($data['sort'] ?? 0);
        $status = intval($data['status'] ?? 0);

        if (empty($name)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $category = ArticleCategory::find($id);
        $category->setAttribute('name', $name);
        $category->setAttribute('pid', $pid);
        $category->setAttribute('cover', $cover);
        $category->setAttribute('color', $color);
        $category->setAttribute('sort', $sort);
        $category->setAttribute('status', $status);
        if (! $category->save()) {
            error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
        }

        return $id;
    }

    /**
     * 修改分类属性.
     * @param int $id 分类ID
     * @param string $field 属性字段
     * @param string $value 属性值
     */
    public function updateField(int $id, string $field, string $value): ArticleCategory
    {
        $value = trim($value);
        if ($value === '') {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        if (! ArticleCategory::hasInfoById($id)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $category = ArticleCategory::find($id);
        // 过滤暂时不支持的字段
        $value = match ($field) {
            'status' => (($value == 'true') || (intval($value) > 0)) ? 1 : 0,
            'sort' => intval($value),
            default => error(ErrorCode::SYSTEM_INVALID_INSTRUCTION_ERROR),
        };

        $category->setAttribute($field, $value);
        if (! $category->save()) {
            error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
        }

        return $category;
    }

    /**
     * 删除分类.
     * @param int $id 分类ID
     * @param bool $force 是否强制删除/物理删除
     * @throws Exception
     */
    public function delete(int $id, bool $force): bool
    {
        // 只能删除无子分类的数据, 否则请先删除子分类
        if (ArticleCategory::hasChildById($id)) {
            error(ErrorCode::ARTICLE_CATEGORY_EXISTS_RELATION_ERROR);
        }

        // 物理删除
        if ($force) {
            $category = ArticleCategory::withTrashed()->find($id);
            if (empty($category)) {
                error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
            }

            $category->forceDelete();
            return true;
        }

        // 软删除
        $category = ArticleCategory::find($id);
        if (empty($category)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $category->delete();
        return true;
    }
}
