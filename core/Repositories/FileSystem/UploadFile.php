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

use Core\Repositories\GenerateIdent\UUID;
use Exception;
use Hyperf\Context\Context;
use Hyperf\Filesystem\FilesystemFactory;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;

use function Hyperf\Support\env;
use function Hyperf\Support\make;

class UploadFile
{
    /**
     * 本地.
     */
    public const TYPE_LOCAL = 'local';

    /**
     * 七牛云.
     */
    public const TYPE_QINIU = 'qiniu';

    /**
     * 文件存储目录.
     */
    public string $path;

    protected FilesystemFactory $factory;

    protected Filesystem $adapter;

    protected UUID $uuid;

    protected string $adapterName;

    protected string $filename;

    public function __construct($adapterName = self::TYPE_QINIU)
    {
        $this->factory = make(FilesystemFactory::class);
        $this->setAdapter($adapterName);
        $this->setPath(sprintf(
            '%s/%d/%d/%d',
            'micro-server',
            date('Y'),
            date('m'),
            date('d')
        ));
        $this->uuid = make(UUID::class);
    }

    /**
     * 文件存储目录.
     */
    public function setPath(string $path): void
    {
        $this->path = rtrim($path, '/');
    }

    /**
     * 设置适配器.
     * @param string $adapterName 适配器名称
     */
    public function setAdapter(string $adapterName): void
    {
        $this->setAdapterName($adapterName);
        $this->adapter = $this->factory->get($this->adapterName);
    }

    /**
     * 资源流上传文件.
     * @param string $contents 字节流数据
     * @param string $extension 文件扩展名
     * @return null|string[]
     * @throws FilesystemException
     */
    public function writeStream(string $contents, string $extension = ''): ?array
    {
        $location = $this->generateLocation($extension);

        // 创建一个内存流并写入字符流数据
        $memoryStream = fopen('php://memory', 'w+');
        try {
            if (empty($memoryStream)) {
                return null;
            }

            fwrite($memoryStream, base64_decode($contents));
            $this->adapter->writeStream($location, $memoryStream);
            // 判断文件是否已上传完成
            if ($this->adapter->fileExists($location)) {
                /** @var FileAttributes $result */
                $result = $this->adapter->listContents($location)->toArray()[0];
                $response = [
                    'thirdParty' => $this->adapterName,
                    'bucket' => $this->getBucket(),
                    'hash' => '',
                    'uuid' => '',
                    'type' => $result->type(),
                    'filename' => $this->filename,
                    'size' => $result->fileSize(),
                    'domain' => $this->getDomain(),
                    'path' => $result->path(),
                    'mimeType' => $result->mimeType(),
                    'ext' => $extension,
                    'width' => 0,
                    'height' => 0,
                    'lastModified' => $result->lastModified(),
                    'url' => $this->getUrl($location),
                ];

                $contextData = $this->getUploadContextData();
                if ($contextData && is_array($contextData)) {
                    isset($contextData['hash']) && $response['hash'] = $contextData['hash'];
                    isset($contextData['uuid']) && $response['uuid'] = $contextData['uuid'];
                    isset($contextData['width']) && $response['width'] = $contextData['width'];
                    isset($contextData['height']) && $response['height'] = $contextData['height'];
                }

                return $response;
            }
        } catch (Exception $e) {
            // TODO 错误处理
        } finally {
            fclose($memoryStream);
        }

        return null;
    }

    /**
     * 生成文件路径位置.
     */
    public function generateLocation(string $extension = ''): string
    {
        $this->filename = $this->uuid->toString();
        return $this->path . '/' . $this->filename . ($extension ? '.' . $extension : '');
    }

    /**
     * 获取域名.
     */
    public function getDomain(): string
    {
        return match ($this->adapterName) {
            self::TYPE_QINIU => ltrim(env('QINIU_DOMAIN'), '/'),
            default => '',
        };
    }

    /**
     * 获取完整地址.
     */
    public function getUrl(string $location): string
    {
        return $this->getDomain() . '/' . $location;
    }

    /**
     * 获取 Bucket.
     */
    public function getBucket(): string
    {
        return match ($this->adapterName) {
            self::TYPE_QINIU => env('QINIU_BUCKET'),
            default => '',
        };
    }

    /**
     * 获取上传完成后响应的上下文数据.
     */
    public function getUploadContextData(?int $coroutineId = null)
    {
        return match ($this->adapterName) {
            self::TYPE_QINIU => Context::get('upload.qiniu.response', null, $coroutineId),
            default => null,
        };
    }

    /**
     * 设置适配器名称.
     */
    protected function setAdapterName(string $adapterName): void
    {
        if (! in_array($adapterName, [self::TYPE_LOCAL, self::TYPE_QINIU])) {
            $adapterName = self::TYPE_QINIU;
        }

        $this->adapterName = $adapterName;
    }
}
