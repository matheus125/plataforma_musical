<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entitlement extends Model
{
    protected $fillable = [
        'user_id',
        'key',
        'value',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}
