<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeSession extends Model
{
    protected $fillable = [
        'user_id',
        'song_id',
        'performance_id',
        'duration_seconds',
        'goal',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }
}
