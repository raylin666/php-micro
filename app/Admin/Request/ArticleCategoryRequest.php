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
namespace App\Admin\Request;

use Hyperf\Validation\Rule;

class ArticleCategoryRequest extends Request
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

    public function addRules(): array
    {
        return [
            'name' => 'required|string|min:1|max:12|unique:article_category,name',
            'pid' => 'numeric|min:0',
            'cover' => 'url',
            'sort' => 'numeric|min:0|max:65535',
            'status' => [Rule::in(['0', '1'])],
        ];
    }

    public function updateRules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:12',
                Rule::unique('article_category')->ignore($this->route('id')),
            ],
            'pid' => 'numeric|min:0',
            'cover' => 'url',
            'sort' => 'numeric|min:0|max:65535',
            'status' => [Rule::in(['0', '1'])],
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
