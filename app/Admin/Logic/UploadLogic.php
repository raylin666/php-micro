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
namespace App\Admin\Logic;

use Core\Repositories\GenerateIdent\UUID;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Filesystem\FilesystemFactory;
use function Hyperf\Support\make;

class UploadLogic extends Logic
{
    #[Inject]
    public FilesystemFactory $filesystemFactory;

    /**
     * 文件资源流上传.
     */
    public function fileStream(string $dataStream): array
    {
        // 判断文件是否已存在媒体资源内, 有则直接取出
        $hashValue = md5($dataStream);
        $filename = md5(make(UUID::class)->toString());
//        $filename = md5(microtime(true) . rand(10000, 99999)) . '.' . $fileInfo['extension'];
//         FileHelper::uploadFileStream($filename, $fileInfo['data']);

        return [];
    }
}
