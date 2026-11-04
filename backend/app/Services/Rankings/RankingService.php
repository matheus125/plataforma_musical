<?php

namespace App\Services\Rankings;

use App\Events\RankingRecalculated;
use App\Models\Performance;
use App\Models\RankingEntry;
use App\Models\RankingPeriod;

class RankingService
{
    public function recalculateWeekly(): RankingPeriod
    {
        $period = RankingPeriod::firstOrCreate(
            [
                'type' => 'weekly',
                'scope' => 'global',
                'starts_at' => now()->startOfWeek(),
            ],
            [
                'ends_at' => now()->endOfWeek(),
                'status' => 'active',
            ],
        );

        $rows = Performance::query()
            ->selectRaw('user_id, max(score_overall) as score, count(*) as performances_count')
            ->where('is_public', true)
            ->whereBetween('created_at', [$period->starts_at, $period->ends_at])
            ->groupBy('user_id')
            ->orderByDesc('score')
            ->get();

        $rank = 1;

        foreach ($rows as $row) {
            RankingEntry::updateOrCreate(
                [
                    'ranking_period_id' => $period->id,
                    'user_id' => $row->user_id,
                    'song_id' => null,
                ],
                [
                    'score' => $row->score ?? 0,
                    'rank' => $rank++,
                    'performances_count' => $row->performances_count,
                ],
            );
        }

        RankingRecalculated::dispatch($period);

        return $period;
    }
}
