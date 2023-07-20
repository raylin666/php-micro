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
namespace Core\Repositories\Filesystem\Qiniu;

use Hyperf\Filesystem\Contract\AdapterFactoryInterface;

class Factory implements AdapterFactoryInterface
{
    public function make(array $options): Adapter
    {
        return new Adapter($options['accessKey'], $options['secretKey'], $options['bucket'], $options['domain']);
    }
}
