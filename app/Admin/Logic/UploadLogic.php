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

use App\Admin\Model\UploadMedia;
use Core\Constants\ErrorCode;
use Core\Repositories\FileSystem\UploadFile;
use function Hyperf\Support\make;

class UploadLogic extends Logic
{
    /**
     * 文件资源流上传.
     */
    public function fileStream(string $dataStream, string $extension): array
    {
        $hashValue = md5($dataStream);
        // 判断文件是否已存在媒体资源内, 有则直接取出
        $uploadMedia = UploadMedia::getFirstByHashThirdParty($hashValue, UploadFile::TYPE_QINIU);
        if (! $uploadMedia) {
            $upload = make(UploadFile::class);
            $result = $upload->writeStream($dataStream, $extension);
            if (empty($result)) {
                error(ErrorCode::FILE_UPLOAD_ERROR);
            }

            $uploadMedia = make(UploadMedia::class);
            $uploadMedia->setAttribute('hash', $hashValue);
            $uploadMedia->setAttribute('name', $result['filename']);
            $uploadMedia->setAttribute('domain', $result['domain']);
            $uploadMedia->setAttribute('key', $result['path']);
            $uploadMedia->setAttribute('mime_type', $result['mimeType']);
            $uploadMedia->setAttribute('size', $result['size']);
            $uploadMedia->setAttribute('ext', $result['ext']);
            $uploadMedia->setAttribute('third_party_hash', $result['hash']);
            $uploadMedia->setAttribute('third_party_uuid', $result['uuid']);
            $uploadMedia->setAttribute('third_party', $result['thirdParty']);
            $uploadMedia->setAttribute('bucket', $result['bucket']);
            if (! $uploadMedia->save()) {
                error(ErrorCode::FILE_UPLOAD_ERROR);
            }
        }

        if ($uploadMedia['deleted_at']) {
            error(ErrorCode::FILE_STREAM_RESOURCE_ALREADY_EXISTS_ERROR);
        }

        $uploadMedia = $uploadMedia->toArray();

        $response['hash'] = $uploadMedia['hash'];
        $response['name'] = $uploadMedia['name'];
        $response['key'] = $uploadMedia['key'];
        $response['mime_type'] = $uploadMedia['mime_type'];
        $response['size'] = $uploadMedia['size'];
        $response['url'] = UploadMedia::getUrl($uploadMedia['domain'], $uploadMedia['key']);
        $response['ext'] = $uploadMedia['ext'];
        return $response;
    }
}
