<?php

namespace App\Jobs\Analytics;

use App\Services\Analytics\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TrackAnalyticsEventJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $payload) {}

    public function handle(AnalyticsService $analytics): void
    {
        $analytics->track($this->payload);
    }
}
