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

use App\Knowledge\Model\ArticleCategory;
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
        $list = ArticleCategory::getParentList();
        foreach ($list as &$item) {
            $item->article_count = 0;
        }

        return $list;
    }

    /**
     * 新增分类.
     * @param array $data 数据内容
     */
    public function add(array $data): int
    {
        $name = $data['name'] ?? '';
        $pid = intval($data['pid'] ?? 0);
        $icon = $data['icon'] ?? '';
        $color = $data['color'] ?? '';
        $sort = intval($data['sort'] ?? 0);
        $status = intval($data['status'] ?? 0);

        if (empty($name)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $category = make(ArticleCategory::class);
        $category->setAttribute('name', $name);
        $category->setAttribute('pid', $pid);
        $category->setAttribute('icon', $icon);
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

        return [
            'id' => $category->getAttributeValue('id'),
            'name' => $category->getAttributeValue('name'),
            'pid' => $category->getAttributeValue('pid'),
            'icon' => $category->getAttributeValue('icon'),
            'color' => $category->getAttributeValue('color'),
            'sort' => $category->getAttributeValue('sort'),
            'status' => $category->getAttributeValue('status'),
            'created_at' => $category->getAttributeValue('created_at'),
            'article_count' => 0,
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
        $icon = $data['icon'] ?? '';
        $color = $data['color'] ?? '';
        $sort = intval($data['sort'] ?? 0);
        $status = intval($data['status'] ?? 0);

        if (empty($name)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $category = ArticleCategory::find($id);
        $category->setAttribute('name', $name);
        $category->setAttribute('pid', $pid);
        $category->setAttribute('icon', $icon);
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
