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

use App\Admin\Request\ArticleCategoryRequest;
use App\Admin\Service\ArticleCategoryService;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class ArticleCategoryController extends Controller
{
    #[Inject]
    protected ArticleCategoryService $articleCategoryService;

    /**
     * 获取分类列表.
     * @return ResponseInterface
     */
    public function list(): ResponseInterface
    {
        return $this->response->json($this->articleCategoryService->list());
    }

    /**
     * 新增分类.
     * @param ArticleCategoryRequest $request
     * @return ResponseInterface
     */
    public function add(ArticleCategoryRequest $request): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->articleCategoryService->add($data));
    }

    /**
     * 获取分类信息.
     * @param int $id 分类ID
     * @return ResponseInterface
     */
    public function info(int $id): ResponseInterface
    {
        return $this->response->json($this->articleCategoryService->info($id));
    }

    /**
     * 更新分类.
     * @param ArticleCategoryRequest $request
     * @param int                    $id
     * @return ResponseInterface
     */
    public function update(ArticleCategoryRequest $request, int $id): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->articleCategoryService->update($id, $data));
    }

    /**
     * 修改分类属性.
     * @param int            $id
     * @param string         $field
     * @return ResponseInterface
     */
    public function updateField(int $id, string $field): ResponseInterface
    {
        $value = $this->request->post('value');
        return $this->response->json($this->articleCategoryService->updateField($id, $field, $value));
    }

    /**
     * 删除分类.
     * @param ArticleCategoryRequest $request
     * @param int                    $id
     * @return ResponseInterface
     * @throws Exception
     */
    public function delete(ArticleCategoryRequest $request, int $id): ResponseInterface
    {
        $data = $request->validated();
        return $this->response->json($this->articleCategoryService->delete($id, boolval($data['force'] ?? false)));
    }
}
