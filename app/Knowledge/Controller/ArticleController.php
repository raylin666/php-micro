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
namespace App\Knowledge\Controller;

use App\Knowledge\Request\ArticleRequest;
use App\Knowledge\Service\ArticleService;
use Core\Constants\Page;
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

    public function add(): ResponseInterface
    {
        return $this->response->json($this->articleService->add());
    }

    public function update(): ResponseInterface
    {
        return $this->response->json($this->articleService->update());
    }

    public function updateField(): ResponseInterface
    {
        return $this->response->json($this->articleService->updateField());
    }

    public function delete(): ResponseInterface
    {
        return $this->response->json($this->articleService->delete());
    }
}
