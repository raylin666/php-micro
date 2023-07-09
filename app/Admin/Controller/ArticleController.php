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

use App\Admin\Request\ArticleRequest;
use App\Admin\Service\ArticleService;
use Core\Constants\Page;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class ArticleController extends Controller
{
    #[Inject]
    protected ArticleService $articleService;

    /**
     * 获取文章列表.
     */
    public function list(ArticleRequest $request): ResponseInterface
    {
        $data = $request->validated();
        $page = intval($data['page'] ?? Page::DEFAULT_PAGE_NUM);
        $size = intval($data['size'] ?? Page::DEFAULT_PAGE_SIZE);
        return $this->response->json($this->articleService->list($page, $size));
    }

    /**
     * 新增文章.
     */
    public function add(ArticleRequest $request): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->articleService->add($data));
    }

    /**
     * 获取文章信息.
     * @param int $id 文章ID
     */
    public function info(int $id): ResponseInterface
    {
        return $this->response->json($this->articleService->info($id));
    }

    /**
     * 更新文章.
     */
    public function update(ArticleRequest $request, int $id): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->articleService->update($id, $data));
    }

    /**
     * 修改文章属性.
     */
    public function updateField(int $id, string $field): ResponseInterface
    {
        $value = $this->request->post('value');
        return $this->response->json($this->articleService->updateField($id, $field, $value));
    }

    /**
     * 删除文章.
     * @throws Exception
     */
    public function delete(ArticleRequest $request, int $id): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->articleService->delete($id, boolval($data['force'] ?? false)));
    }
}
