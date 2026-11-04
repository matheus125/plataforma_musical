<?php

namespace App\Jobs\Media;

use App\Jobs\VocalAnalysis\RunVocalAnalysisJob;
use App\Models\MediaAsset;
use App\Services\Audio\AudioProcessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessUploadedAudioJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $mediaAssetId,
        public int $performanceId,
    ) {}

    public function handle(AudioProcessingService $audioProcessing): void
    {
        $mediaAsset = MediaAsset::findOrFail($this->mediaAssetId);
        $audioProcessing->markAsProcessed($mediaAsset);

        RunVocalAnalysisJob::dispatch($this->performanceId);
    }
}
