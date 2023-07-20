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
namespace App\Admin\Controller;

use App\Admin\Request\UploadRequest;
use App\Admin\Service\UploadService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class UploadController extends Controller
{
    #[Inject]
    protected UploadService $uploadService;

    /**
     * 文件资源流上传.
     * @param UploadRequest $request
     * @return ResponseInterface
     */
    public function fileStream(UploadRequest $request): ResponseInterface
    {
        $data = $request->validated();
        $dataStream = strval($data['stream'] ?? '');
        $extension = strval($request->post('extension', ''));
        return $this->response->json($this->uploadService->fileStream($dataStream, $extension));
    }
}
