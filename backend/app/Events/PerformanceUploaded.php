<?php

namespace App\Events;

use App\Models\Performance;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PerformanceUploaded
{
    use Dispatchable, SerializesModels;

    public function __construct(public Performance $performance) {}
}
