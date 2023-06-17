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
namespace App\Account\Logic;

use Core\Traits\AccountTrait;
use Core\Traits\LogicTrait;
use Core\Abstract\LogicAbstract;

abstract class Logic extends LogicAbstract
{
    use LogicTrait;
    use AccountTrait;
}
