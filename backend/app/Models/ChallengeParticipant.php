<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeParticipant extends Model
{
    protected $fillable = [
        'challenge_id',
        'user_id',
        'status',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }
}
