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
namespace Core\Repositories\GenerateIdent;

use Ramsey\Uuid\Uuid as RamseyUUID;
use Ramsey\Uuid\UuidInterface;

class UUID
{
    protected UuidInterface $uuid;

    /**
     * 初始化 UUID 类.
     * @param string $version
     */
    public function __construct(string $version = 'default')
    {
        $this->uuid = match ($version) {
            'v1' => RamseyUUID::uuid1(),
            default => RamseyUUID::uuid4(),
        };
    }

    /**
     * 生成 UUID.
     * @return string
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
