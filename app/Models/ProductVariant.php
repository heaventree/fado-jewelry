<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'metal_id',
        'second_metal_id',
        'gemstone_id',
        'sku',
        'price_eur',
        'stock',
        'is_active',
        'colour',
    ];

    protected $casts = [
        'price_eur' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function metal(): BelongsTo
    {
        return $this->belongsTo(Metal::class);
    }

    public function secondMetal(): BelongsTo
    {
        return $this->belongsTo(Metal::class, 'second_metal_id');
    }

    public function gemstone(): BelongsTo
    {
        return $this->belongsTo(Gemstone::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'variant_id');
    }

    public function getLabelAttribute(): string
    {
        $label = $this->metal->name;

        if ($this->gemstone) {
            $label .= ' / ' . $this->gemstone->name;
        }

        return $label;
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
