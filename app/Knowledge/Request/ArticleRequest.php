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
namespace App\Knowledge\Request;

use Hyperf\Validation\Rule;

class ArticleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Public rules.
     */
    public function commonRules(): array
    {
        return [];
    }

    public function listRules(): array
    {
        return [
            'page' => 'numeric|min:1',
            'size' => 'numeric|min:1',
        ];
    }

    public function addRules(): array
    {
        return [
            'title' => 'required|string|min:3|max:30',
            'author' => 'required|string',
            'summary' => 'required|max:140',
            'cover' => 'required|url',
            'sort' => 'numeric|min:0|max:65535',
            'recommend_flag' => 'boolean',
            'commented_flag' => 'boolean',
            'status' => [Rule::in(['0', '1'])],
            'user_id' => 'required|numeric',
            'source' => 'string|max:32',
            'source_url' => 'url',
            'content' => 'required',
            'keyword' => 'json',
            'attachment_path' => 'json',
        ];
    }

    public function updateRules(): array
    {
        return [
            'title' => 'required|string|min:3|max:30',
            'author' => 'required|string',
            'summary' => 'required|max:140',
            'cover' => 'required|url',
            'sort' => 'numeric|min:0|max:65535',
            'recommend_flag' => 'boolean',
            'commented_flag' => 'boolean',
            'status' => [Rule::in(['0', '1'])],
            'user_id' => 'required|numeric',
            'source' => 'string|max:32',
            'source_url' => 'url',
            'content' => 'required',
            'keyword' => 'json',
            'attachment_path' => 'json',
        ];
    }

    public function deleteRules(): array
    {
        return [
            'force' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [];
    }
}
