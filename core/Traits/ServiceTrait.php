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
namespace Core\Traits;

use Hyperf\Contract\LengthAwarePaginatorInterface;

trait ServiceTrait
{
    /**
     * 组装分页数据 (用来响应数据).
     * @param LengthAwarePaginatorInterface $lengthAwarePaginator
     * @return array
     */
    public function assemblePaginator(LengthAwarePaginatorInterface $lengthAwarePaginator): array
    {
        return [
            'list' => $lengthAwarePaginator->items(),
            'total' => $lengthAwarePaginator->total(),
            'current_page' => $lengthAwarePaginator->currentPage(),
            'last_page' => $lengthAwarePaginator->lastPage(),
            'size' => $lengthAwarePaginator->perPage(),
            'prev_page_url' => $lengthAwarePaginator->previousPageUrl(),
            'next_page_url' => $lengthAwarePaginator->nextPageUrl(),
        ];
    }
}
