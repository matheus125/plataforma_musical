<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'provider',
        'provider_subscription_id',
        'status',
        'current_period_starts_at',
        'current_period_ends_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'current_period_starts_at' => 'datetime',
            'current_period_ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }
}
