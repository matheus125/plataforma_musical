<?php

namespace App\Events;

use App\Models\Performance;
use App\Models\VocalAnalysis;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PerformanceAnalysisCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Performance $performance,
        public VocalAnalysis $analysis,
    ) {}
}
