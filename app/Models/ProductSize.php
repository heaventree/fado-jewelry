<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSize extends Model
{
    protected $fillable = ['product_id', 'us_size', 'stock'];

    protected $casts = [
        'us_size' => 'decimal:1',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
