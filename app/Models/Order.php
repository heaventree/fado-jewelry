<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** Valid statuses in lifecycle order, with display config. */
    public const STATUSES = [
        'pending'    => ['label' => 'Pending',    'colour' => 'warning',   'icon' => 'solar:clock-circle-broken'],
        'processing' => ['label' => 'Processing', 'colour' => 'info',      'icon' => 'solar:settings-broken'],
        'shipped'    => ['label' => 'Shipped',    'colour' => 'primary',   'icon' => 'solar:box-broken'],
        'delivered'  => ['label' => 'Delivered',  'colour' => 'success',   'icon' => 'solar:check-circle-broken'],
        'cancelled'  => ['label' => 'Cancelled',  'colour' => 'danger',    'icon' => 'solar:close-circle-broken'],
        'refunded'   => ['label' => 'Refunded',   'colour' => 'secondary', 'icon' => 'solar:refresh-broken'],
    ];

    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'shipping_total',
        'total',
        'currency_code',
        'payment_method',
        'shipping_address',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'shipping_total'   => 'decimal:2',
        'total'            => 'decimal:2',
        'shipping_address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getIsGuestAttribute(): bool
    {
        return is_null($this->user_id);
    }

    /** Zero-padded order number, e.g. #000042 */
    public function getOrderNumberAttribute(): string
    {
        return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /** Customer display name from account or shipping address. */
    public function getCustomerNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->shipping_address['name'] ?? 'Guest';
    }

    /** Customer email from account or shipping address. */
    public function getCustomerEmailAttribute(): string
    {
        if ($this->user) {
            return $this->user->email;
        }
        return $this->shipping_address['email'] ?? '—';
    }

    /** Bootstrap colour key for the current status badge. */
    public function getStatusColourAttribute(): string
    {
        return self::STATUSES[$this->status]['colour'] ?? 'secondary';
    }

    /** Human-readable status label. */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? ucfirst($this->status);
    }

    /** Currency symbol for display. */
    public function getCurrencySymbolAttribute(): string
    {
        return match ($this->currency_code) {
            'EUR'   => '€',
            'USD'   => '$',
            'GBP'   => '£',
            default => $this->currency_code . ' ',
        };
    }
}
