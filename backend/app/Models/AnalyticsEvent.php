<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'session_id',
        'event_name',
        'entity_type',
        'entity_id',
        'metadata',
        'ip_hash',
        'user_agent',
        'country',
        'locale',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }
}
