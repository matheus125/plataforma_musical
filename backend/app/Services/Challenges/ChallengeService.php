<?php

namespace App\Services\Challenges;

use App\Events\ChallengeJoined;
use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\User;

class ChallengeService
{
    public function join(Challenge $challenge, User $user): ChallengeParticipant
    {
        $participant = ChallengeParticipant::updateOrCreate(
            [
                'challenge_id' => $challenge->id,
                'user_id' => $user->id,
            ],
            [
                'status' => 'joined',
                'joined_at' => now(),
            ],
        );

        ChallengeJoined::dispatch($challenge, $user);

        return $participant;
    }

    public function recalculateLeaderboard(int $challengeId): void
    {
        Challenge::query()->whereKey($challengeId)->update([
            'updated_at' => now(),
        ]);
    }
}
