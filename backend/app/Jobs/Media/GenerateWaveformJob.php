<?php

namespace App\Jobs\Media;

use App\Models\MediaAsset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateWaveformJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $mediaAssetId) {}

    public function handle(): void
    {
        $mediaAsset = MediaAsset::findOrFail($this->mediaAssetId);
        $mediaAsset->update([
            'metadata' => array_merge($mediaAsset->metadata ?? [], [
                'waveform_status' => 'pending_real_processor',
            ]),
        ]);
    }
}
