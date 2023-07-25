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

use App\Admin\Request\ChatBotRequest;
use App\Admin\Service\ChatBotService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class ChatBotController extends Controller
{
    #[Inject]
    protected ChatBotService $chatBotService;

    /**
     * 获取场景分类列表.
     */
    public function list(): ResponseInterface
    {
        return $this->response->json($this->chatBotService->list());
    }

    /**
     * 获取场景分类选择列表.
     * @return ResponseInterface
     */
    public function listSelect(): ResponseInterface
    {
        return $this->response->json($this->chatBotService->listSelect());
    }

    /**
     * 新增场景分类.
     */
    public function add(ChatBotRequest $request): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->chatBotService->add($data));
    }

    /**
     * 获取场景分类信息.
     * @param int $id 分类ID
     */
    public function info(int $id): ResponseInterface
    {
        return $this->response->json($this->chatBotService->info($id));
    }

    /**
     * 更新场景分类.
     */
    public function update(ChatBotRequest $request, int $id): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->chatBotService->update($id, $data));
    }

    /**
     * 修改场景分类属性.
     */
    public function updateField(int $id, string $field): ResponseInterface
    {
        $value = $this->request->post('value');
        return $this->response->json($this->chatBotService->updateField($id, $field, $value));
    }

    /**
     * 删除场景分类.
     */
    public function delete(ChatBotRequest $request, int $id): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->chatBotService->delete($id, boolval($data['force'] ?? false)));
    }
}
