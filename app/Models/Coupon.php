<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order',
        'usage_limit',
        'times_used',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value'         => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'expires_at'    => 'datetime',
        'is_active'     => 'boolean',
    ];

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsUsedUpAttribute(): bool
    {
        return $this->usage_limit && $this->times_used >= $this->usage_limit;
    }

    /** Whether this coupon can currently be applied to an order. */
    public function getIsValidAttribute(): bool
    {
        return $this->is_active && !$this->is_expired && !$this->is_used_up;
    }

    public function getDiscountLabelAttribute(): string
    {
        return $this->type === 'percent'
            ? number_format((float) $this->value, 0) . '%'
            : '€' . number_format((float) $this->value, 2);
    }

    /** Calculate discount amount for a given order subtotal. */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            return round($subtotal * ($this->value / 100), 2);
        }

        return min((float) $this->value, $subtotal);
    }
}
