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
namespace Core\Helper;

class TreeHelper
{
    /**
     * @param array $categories 分类数据集
     * @param int $parentId 父级分类ID
     * @return array
     */
    public static function buildCategory(array $categories, int $parentId = 0): array
    {
        $branch = [];
        foreach ($categories as $key => $category) {
            if ($category['pid'] == $parentId) {
                unset($categories[$key]);
                $category['children'] = static::buildCategory($categories, $category['id']);
                $branch[] = $category;
            }
        }

        return $branch;
    }
}
