<?php

namespace App\Services\Audio;

use App\Models\MediaAsset;

class AudioProcessingService
{
    public function markAsProcessed(MediaAsset $mediaAsset): MediaAsset
    {
        $mediaAsset->update([
            'status' => 'processed',
            'metadata' => array_merge($mediaAsset->metadata ?? [], [
                'processing_note' => 'Foundation placeholder. Transcoding and waveform generation will run here.',
                'processed_at' => now()->toISOString(),
            ]),
        ]);

        return $mediaAsset->refresh();
    }
}
