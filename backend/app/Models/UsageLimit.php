<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageLimit extends Model
{
    protected $fillable = [
        'user_id',
        'key',
        'used',
        'limit',
        'period_start',
        'period_end',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }
}
