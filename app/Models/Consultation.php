<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'preferred_contact',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function getIsReadAttribute(): bool
    {
        return ! is_null($this->read_at);
    }

    public function getPreferredContactLabelAttribute(): string
    {
        return match ($this->preferred_contact) {
            'phone' => 'Phone call',
            'email' => 'Email',
            default => ucfirst($this->preferred_contact),
        };
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
