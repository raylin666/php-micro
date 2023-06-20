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
use Core\Constants\ErrorCode;
use Hyperf\Contract\LengthAwarePaginatorInterface;

class ArticleLogic extends Logic
{
    /**
     * 获取文章列表.
     * @param int $page
     * @param int $size
     * @return LengthAwarePaginatorInterface
     */
    public function list(int $page, int $size): LengthAwarePaginatorInterface
    {
        if (($page < 1) || ($size < 1)) {
            error(ErrorCode::SYSTEM_REQUEST_PARAMS_ERROR);
        }

        return Article::getPageList($page, $size);
    }
}
