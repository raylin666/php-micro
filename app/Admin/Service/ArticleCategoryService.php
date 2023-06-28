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
namespace App\Admin\Service;

use App\Admin\Logic\ArticleCategoryLogic;
use Exception;
use Hyperf\Di\Annotation\Inject;

class ArticleCategoryService extends Service
{
    #[Inject]
    public ArticleCategoryLogic $articleCategoryLogic;

    /**
     * 获取分类列表.
     */
    public function list(): array
    {
        $list = $this->articleCategoryLogic->list();
        return $list->toArray();
    }

    /**
     * 新增分类.
     * @param array $data 数据集合
     */
    public function add(array $data): array
    {
        $id = $this->articleCategoryLogic->add($data);
        return $this->info($id);
    }

    /**
     * 获取分类信息.
     * @param int $id 分类ID
     */
    public function info(int $id): array
    {
        return $this->articleCategoryLogic->info($id);
    }

    /**
     * 更新分类.
     * @param int $id 分类ID
     * @param array $data 数据集合
     */
    public function update(int $id, array $data): array
    {
        $id = $this->articleCategoryLogic->update($id, $data);
        return $this->info($id);
    }

    /**
     * 修改分类属性.
     * @param int $id 分类ID
     * @param string $field 属性字段
     * @param string $value 属性值
     */
    public function updateField(int $id, string $field, string $value): array
    {
        $category = $this->articleCategoryLogic->updateField($id, $field, $value);

        return [
            'id' => $category->getAttributeValue('id'),
            $field => $category->getAttributeValue($field),
        ];
    }

    /**
     * 删除分类.
     * @param int $id 分类ID
     * @param bool $force 是否强制删除/物理删除
     * @throws Exception
     */
    public function delete(int $id, bool $force = false): array
    {
        $this->articleCategoryLogic->delete($id, $force);
        return ['id' => $id];
    }
}
