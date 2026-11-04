<?php

namespace App\Events;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChallengeJoined
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Challenge $challenge,
        public User $user,
    ) {}
}
