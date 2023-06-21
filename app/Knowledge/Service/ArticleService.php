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
use Core\Constants\ErrorCode;
use Core\Constants\Page;
use Hyperf\Di\Annotation\Inject;

class ArticleService extends Service
{
    #[Inject]
    public ArticleLogic $articleLogic;

    /**
     * 获取文章列表.
     * @param int $page
     * @param int $size
     * @return array
     */
    public function list(int $page = Page::DEFAULT_PAGE_NUM, int $size = Page::DEFAULT_PAGE_SIZE): array
    {
        $lengthAwarePaginator = $this->articleLogic->list($page, $size);
        return $this->assemblePaginator($lengthAwarePaginator);
    }

    /**
     * 新增文章.
     * @param array $data
     * @return array
     */
    public function add(array $data): array
    {
        $id = $this->articleLogic->add($data);
        return $this->articleLogic->info($id);
    }

    /**
     * 获取文章信息.
     * @param int $id 文章ID
     * @return array
     */
    public function info(int $id): array
    {
        return $this->articleLogic->info($id);
    }

    public function update()
    {
    }

    public function updateField()
    {
    }

    public function delete()
    {
    }
}
