<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
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
            'banner_image'       => ['nullable', 'image', 'max:8192'],
            'banner_title'       => ['nullable', 'string', 'max:255'],
            'banner_description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
