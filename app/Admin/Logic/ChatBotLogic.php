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

use App\Admin\Model\ChatbotCategoryScene;
use Core\Constants\ErrorCode;
use Core\Helper\TreeHelper;
use Exception;

use function Hyperf\Support\make;

class ChatBotLogic extends Logic
{
    /**
     * 获取场景分类列表.
     */
    public function list(): array
    {
        $list = ChatbotCategoryScene::getList();
        return TreeHelper::buildCategory($list->toArray());
    }

    /**
     * 新增场景分类.
     * @param array $data 数据内容
     */
    public function add(array $data): int
    {
        $name = $data['name'] ?? '';
        $pid = intval($data['pid'] ?? 0);
        $icon = $data['icon'] ?? '';
        $describe = $data['describe'] ?? '';
        $question = $data['question'] ?? '';
        $sort = intval($data['sort'] ?? 0);
        $status = intval($data['status'] ?? 0);

        if (empty($name)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $categoryScene = make(ChatbotCategoryScene::class);
        $categoryScene->setAttribute('name', $name);
        $categoryScene->setAttribute('pid', $pid);
        $categoryScene->setAttribute('icon', $icon);
        $categoryScene->setAttribute('describe', $describe);
        $categoryScene->setAttribute('question', $question);
        $categoryScene->setAttribute('sort', $sort);
        $categoryScene->setAttribute('status', $status);
        if (! $categoryScene->save()) {
            error(ErrorCode::SYSTEM_INSERT_DATA_ERROR);
        }

        return $categoryScene->getAttributeValue('id');
    }

    /**
     * 获取场景分类信息.
     * @param int $id 分类ID
     */
    public function info(int $id): array
    {
        if ($id < 1) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $categoryScene = ChatbotCategoryScene::getInfoById($id);
        if (empty($categoryScene)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $categoryScene = $categoryScene->toArray();

        return [
            'id' => $id,
            'name' => $categoryScene['name'],
            'pid' => $categoryScene['pid'],
            'icon' => $categoryScene['icon'],
            'describe' => $categoryScene['describe'],
            'question' => $categoryScene['question'],
            'sort' => $categoryScene['sort'],
            'status' => $categoryScene['status'],
            'created_at' => $categoryScene['created_at'],
        ];
    }

    /**
     * 更新场景分类.
     * @param int $id 分类ID
     * @param array $data 更新内容
     */
    public function update(int $id, array $data): int
    {
        if (! ChatbotCategoryScene::hasInfoById($id)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $name = $data['name'] ?? '';
        $pid = intval($data['pid'] ?? 0);
        $icon = $data['icon'] ?? '';
        $describe = $data['describe'] ?? '';
        $question = $data['question'] ?? '';
        $sort = intval($data['sort'] ?? 0);
        $status = intval($data['status'] ?? 0);

        if (empty($name)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        $categoryScene = ChatbotCategoryScene::find($id);
        $categoryScene->setAttribute('name', $name);
        $categoryScene->setAttribute('pid', $pid);
        $categoryScene->setAttribute('icon', $icon);
        $categoryScene->setAttribute('describe', $describe);
        $categoryScene->setAttribute('question', $question);
        $categoryScene->setAttribute('sort', $sort);
        $categoryScene->setAttribute('status', $status);
        if (! $categoryScene->save()) {
            error(ErrorCode::SYSTEM_UPDATE_DATA_ERROR);
        }

        return $id;
    }

    /**
     * 修改场景分类属性.
     * @param int $id 分类ID
     * @param string $field 属性字段
     * @param string $value 属性值
     */
    public function updateField(int $id, string $field, string $value): ChatbotCategoryScene
    {
        $value = trim($value);
        if ($value === '') {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        if (! ChatbotCategoryScene::hasInfoById($id)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $category = ChatbotCategoryScene::find($id);
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
     * 删除场景分类.
     * @param int $id 分类ID
     * @param bool $force 是否强制删除/物理删除
     * @throws Exception
     */
    public function delete(int $id, bool $force): bool
    {
        // 只能删除无子分类的数据, 否则请先删除子分类
        if (ChatbotCategoryScene::hasChildById($id)) {
            error(ErrorCode::SYSTEM_EXISTS_RELATION_ERROR);
        }

        // 物理删除
        if ($force) {
            $category = ChatbotCategoryScene::withTrashed()->find($id);
            if (empty($category)) {
                error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
            }

            $category->forceDelete();
            return true;
        }

        // 软删除
        $category = ChatbotCategoryScene::find($id);
        if (empty($category)) {
            error(ErrorCode::SYSTEM_DATA_NOT_EXISTS_ERROR);
        }

        $category->delete();
        return true;
    }
}
