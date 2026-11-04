<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocalProgressSnapshot extends Model
{
    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'period_type',
        'avg_score_overall',
        'avg_score_pitch',
        'avg_score_rhythm',
        'stability_score',
        'performances_count',
        'practice_seconds',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'metadata' => 'array',
        ];
    }
}
