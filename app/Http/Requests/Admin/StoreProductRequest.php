<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'                      => ['required', 'string', 'max:255'],
            'slug'                      => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/'],
            'short_description'         => ['nullable', 'string', 'max:500'],
            'description'               => ['nullable', 'string'],
            'is_active'                 => ['nullable', 'boolean'],
            'category_ids'              => ['nullable', 'array'],
            'category_ids.*'            => ['integer', 'exists:categories,id'],
            'collection_ids'            => ['nullable', 'array'],
            'collection_ids.*'          => ['integer', 'exists:collections,id'],
            'variants'                  => ['nullable', 'array'],
            'variants.*.metal_id'       => ['required_with:variants', 'integer', 'exists:metals,id'],
            'variants.*.gemstone_id'    => ['nullable', 'integer', 'exists:gemstones,id'],
            'variants.*.sku'            => ['nullable', 'string', 'max:100'],
            'variants.*.price_eur'      => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock'          => ['required_with:variants', 'integer', 'min:0'],
            'variants.*.is_active'      => ['nullable', 'boolean'],
            'sizes'                     => ['nullable', 'array'],
            'sizes.*.enabled'           => ['nullable', 'boolean'],
            'sizes.*.stock'             => ['nullable', 'integer', 'min:0'],
            'images'                    => ['nullable', 'array'],
            'images.*'                  => ['image', 'max:5120'],
            'image_quality'             => ['nullable', 'in:high,balanced,small'],
        ];
    }
}
