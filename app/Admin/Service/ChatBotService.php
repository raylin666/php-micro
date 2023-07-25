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

use Exception;
use App\Admin\Logic\ChatBotLogic;
use Hyperf\Di\Annotation\Inject;

class ChatBotService extends Service
{
    #[Inject]
    public ChatBotLogic $chatBotLogic;

    /**
     * 获取场景分类列表.
     */
    public function list(): array
    {
        $list = $this->chatBotLogic->list();
        return ['list' => $list];
    }

    /**
     * 获取场景分类选择列表.
     */
    public function listSelect(): array
    {
        $list = $this->chatBotLogic->listSelect();
        return ['list' => $list->toArray()];
    }

    /**
     * 新增场景分类.
     * @param array $data 数据集合
     */
    public function add(array $data): array
    {
        $id = $this->chatBotLogic->add($data);
        return $this->info($id);
    }

    /**
     * 获取场景分类信息.
     * @param int $id 分类ID
     */
    public function info(int $id): array
    {
        return $this->chatBotLogic->info($id);
    }

    /**
     * 更新场景分类.
     * @param int $id 分类ID
     * @param array $data 数据集合
     */
    public function update(int $id, array $data): array
    {
        $id = $this->chatBotLogic->update($id, $data);
        return $this->info($id);
    }

    /**
     * 修改场景分类属性.
     * @param int $id 分类ID
     * @param string $field 属性字段
     * @param string $value 属性值
     */
    public function updateField(int $id, string $field, string $value): array
    {
        $category = $this->chatBotLogic->updateField($id, $field, $value);

        return [
            'id' => $category->getAttributeValue('id'),
            $field => $category->getAttributeValue($field),
        ];
    }

    /**
     * 删除场景分类.
     * @param int $id 分类ID
     * @param bool $force 是否强制删除/物理删除
     * @throws Exception
     */
    public function delete(int $id, bool $force = false): array
    {
        $this->chatBotLogic->delete($id, $force);
        return ['id' => $id];
    }
}
