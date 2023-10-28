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
namespace App\Api\Service;

use App\Api\Logic\ArticleLogic;
use Core\Constants\Page;
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
     * 获取文章信息.
     * @param int $id 文章ID
     */
    public function info(int $id): array
    {
        return $this->articleLogic->info($id);
    }
}
