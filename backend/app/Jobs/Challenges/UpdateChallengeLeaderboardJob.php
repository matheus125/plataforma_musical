<?php

namespace App\Jobs\Challenges;

use App\Services\Challenges\ChallengeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateChallengeLeaderboardJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $challengeId) {}

    public function handle(ChallengeService $challengeService): void
    {
        $challengeService->recalculateLeaderboard($this->challengeId);
    }
}
