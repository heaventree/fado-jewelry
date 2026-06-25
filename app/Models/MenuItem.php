<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id', 'parent_id', 'label', 'url',
        'type', 'reference_id', 'target', 'sort_order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function getResolvedUrl(): string
    {
        if ($this->type === 'category') {
            $slug = Category::find($this->reference_id)?->slug;
            return $slug ? route('shop.category', $slug) : '#';
        }

        if ($this->type === 'collection') {
            $slug = Collection::find($this->reference_id)?->slug;
            return $slug ? route('shop.collection', $slug) : '#';
        }

        return $this->url ?? '#';
    }
}
