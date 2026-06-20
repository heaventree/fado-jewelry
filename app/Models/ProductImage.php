<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'sort_order', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Product image paths are plain public/ assets (e.g. "images/products/demo/..."),
     * not files on Laravel's storage disk, so this resolves via asset() not Storage::url().
     */
    public function getUrlAttribute(): string
    {
        return asset($this->path);
    }
}
