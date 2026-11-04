<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeReward extends Model
{
    protected $fillable = [
        'challenge_id',
        'type',
        'name',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
