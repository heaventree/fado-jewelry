<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['code', 'name', 'rate', 'is_default', 'region_codes'];

    protected $casts = [
        'rate' => 'decimal:6',
        'is_default' => 'boolean',
        'region_codes' => 'array',
    ];
}
