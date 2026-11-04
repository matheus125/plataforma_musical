<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingEntry extends Model
{
    protected $fillable = [
        'ranking_period_id',
        'user_id',
        'song_id',
        'score',
        'rank',
        'performances_count',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }
}
