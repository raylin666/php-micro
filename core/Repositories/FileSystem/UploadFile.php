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
namespace Core\Repositories\FileSystem;

use Exception;
use Hyperf\Filesystem\FilesystemFactory;
use function Hyperf\Support\make;

class UploadFile
{
    /**
     * 解码 BASE64 资源流信息.
     * @param string $base64String
     * @return array|null
     */
    public static function unpackBase64Resource(string $base64String): ?array
    {
        try {
            $unpack = explode(',', $base64String);
            $info = $unpack[0];
            unset($unpack[0]);

            $data = implode('', $unpack);
            preg_match('/data:(\w+\/\w+);base64/', $info, $matches);
            $imageType = $matches[1];
            $extension = match ($imageType) {
                'image/jpg', 'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                default => 'txt',
            };

            return [
                'mimeType' => $imageType,
                'extension' => $extension,
                'data' => $data,
            ];
        } catch (Exception) {
            return null;
        }
    }

    public static function uploadFileStream(string $filename, $contents, $adapterName = 'qiniu')
    {
        $location = sprintf('micro-server/%s/%s', date('Ymd'), $filename);
        $factory = make(FilesystemFactory::class);
        $adapter = $factory->get($adapterName);
        $adapter->writeStream($location, $contents);
    }
}
