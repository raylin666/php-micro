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
use Hyperf\Di\Annotation\Inject;

class ArticleService extends Service
{
    #[Inject]
    public ArticleLogic $articleLogic;

    public function list()
    {

    }
}
