<?php

namespace App\Jobs\Rankings;

use App\Services\Rankings\RankingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateWeeklyRankingJob implements ShouldQueue
{
    use Queueable;

    public function handle(RankingService $rankingService): void
    {
        $rankingService->recalculateWeekly();
    }
}
