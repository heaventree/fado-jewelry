<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:255'],
            'slug'               => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/'],
            'parent_id'          => ['nullable', 'integer', 'exists:categories,id'],
            'sort_order'         => ['nullable', 'integer', 'min:0'],
            'banner_image'       => ['nullable', 'image', 'max:8192'],
            'remove_banner'      => ['nullable', 'boolean'],
            'thumbnail_image'    => ['nullable', 'image', 'max:8192'],
            'remove_thumbnail'   => ['nullable', 'boolean'],
            'image_quality'      => ['nullable', 'in:high,balanced,small'],
            'banner_title'       => ['nullable', 'string', 'max:255'],
            'banner_description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
