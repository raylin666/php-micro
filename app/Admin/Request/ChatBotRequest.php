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

class ChatBotRequest extends Request
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
            'name' => [
                'required',
                'string',
                'min:1',
                'max:12',
                Rule::unique('chatbot_category_scene')->where(function ($query) {
                    $query->where(['name' => $this->input('name'), 'pid' => $this->input('pid')]);
                }),
            ],
            'pid' => 'numeric|min:0',
            'icon' => 'string',
            'describe' => 'string',
            'question' => 'string',
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
                Rule::unique('chatbot_category_scene')->where(function ($query) {
                    $query->where(['name' => $this->input('name'), 'pid' => $this->input('pid')])->where('id', '<>', $this->route('id'));
                }),
            ],
            'pid' => 'numeric|min:0',
            'icon' => 'string',
            'describe' => 'string',
            'question' => 'string',
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
