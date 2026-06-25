<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['name', 'location'];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function scopeWithItems($query)
    {
        return $query->with(['items' => function ($q) {
            $q->whereNull('parent_id')->orderBy('sort_order')
              ->with(['children' => fn ($c) => $c->orderBy('sort_order')]);
        }]);
    }

    public static function getByLocation(string $location): ?self
    {
        return static::withItems()->where('location', $location)->first();
    }
}
