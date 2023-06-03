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
namespace App\Account\Model;

use Core\Model\Model as ModelAbstract;
use Core\Traits\ModelTrait;

abstract class Model extends ModelAbstract
{
    use ModelTrait;
}
