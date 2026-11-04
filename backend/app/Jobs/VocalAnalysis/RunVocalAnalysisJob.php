<?php

namespace App\Jobs\VocalAnalysis;

use App\Events\PerformanceAnalysisCompleted;
use App\Models\Performance;
use App\Services\VocalAnalysis\VocalAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RunVocalAnalysisJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $performanceId) {}

    public function handle(VocalAnalysisService $vocalAnalysis): void
    {
        $performance = Performance::with('mediaAsset')->findOrFail($this->performanceId);
        $analysis = $vocalAnalysis->createPlaceholderAnalysis($performance);

        PerformanceAnalysisCompleted::dispatch($performance, $analysis);
    }
}
