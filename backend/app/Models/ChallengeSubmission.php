<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeSubmission extends Model
{
    protected $fillable = [
        'challenge_id',
        'performance_id',
        'user_id',
        'score',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }
}
