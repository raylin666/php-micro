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
namespace App\Admin\Service;

use App\Admin\Logic\UploadLogic;
use Hyperf\Di\Annotation\Inject;

class UploadService extends Service
{
    #[Inject]
    public UploadLogic $uploadLogic;

    /**
     * 文件资源流上传.
     */
    public function fileStream(string $dataStream): array
    {
        return $this->uploadLogic->fileStream($dataStream);
    }
}
