<?php

namespace App\Events;

use App\Models\RankingPeriod;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RankingRecalculated
{
    use Dispatchable, SerializesModels;

    public function __construct(public RankingPeriod $rankingPeriod) {}
}
