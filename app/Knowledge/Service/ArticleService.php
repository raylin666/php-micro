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
namespace App\Knowledge\Service;

use App\Knowledge\Logic\ArticleLogic;
use Core\Constants\Page;
use Exception;
use Hyperf\Di\Annotation\Inject;

class ArticleService extends Service
{
    #[Inject]
    public ArticleLogic $articleLogic;

    /**
     * 获取文章列表.
     * @param int $page 页码
     * @param int $size 数量
     */
    public function list(int $page = Page::DEFAULT_PAGE_NUM, int $size = Page::DEFAULT_PAGE_SIZE): array
    {
        $lengthAwarePaginator = $this->articleLogic->list($page, $size);
        return $this->assemblePaginator($lengthAwarePaginator);
    }

    /**
     * 新增文章.
     * @param array $data 数据集合
     */
    public function add(array $data): array
    {
        $id = $this->articleLogic->add($data);
        return $this->info($id);
    }

    /**
     * 获取文章信息.
     * @param int $id 文章ID
     */
    public function info(int $id): array
    {
        return $this->articleLogic->info($id);
    }

    /**
     * 更新文章.
     * @param int $id 文章ID
     * @param array $data 数据集合
     */
    public function update(int $id, array $data): array
    {
        $id = $this->articleLogic->update($id, $data);
        return $this->info($id);
    }

    /**
     * 修改文章属性.
     * @param int $id 文章ID
     * @param string $field 属性字段
     * @param string $value 属性值
     */
    public function updateField(int $id, string $field, string $value): array
    {
        $article = $this->articleLogic->updateField($id, $field, $value);

        return [
            'id' => $article->getAttributeValue('id'),
            $field => $article->getAttributeValue($field),
        ];
    }

    /**
     * 删除文章.
     * @param int $id 文章ID
     * @param bool $force 是否强制删除/物理删除
     * @throws Exception
     */
    public function delete(int $id, bool $force = false): array
    {
        $this->articleLogic->delete($id, $force);
        return ['id' => $id];
    }
}
