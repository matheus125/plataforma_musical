<?php

namespace App\Actions\Performances;

use App\Events\PerformanceUploaded;
use App\Jobs\Media\ProcessUploadedAudioJob;
use App\Models\Performance;
use App\Models\Song;
use App\Models\User;
use App\Services\Media\MediaStorageService;
use App\Services\VocalAnalysis\TemporaryScoringService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;

class PublishPerformanceAction
{
    public function __construct(
        private readonly MediaStorageService $mediaStorage,
        private readonly TemporaryScoringService $temporaryScoring,
    ) {}

    public function execute(User $user, Song $song, UploadedFile $audio, ?int $durationSeconds, bool $isPublic): Performance
    {
        $mediaAsset = $this->mediaStorage->storePerformanceAudio($audio, $user);
        $scores = $this->temporaryScoring->generate();

        $performance = Performance::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $user->id,
            'song_id' => $song->id,
            'media_asset_id' => $mediaAsset->id,
            'audio_url' => $mediaAsset->public_url,
            'status' => 'published',
            'visibility' => $isPublic ? 'public' : 'private',
            'published_at' => $isPublic ? now() : null,
            'duration_seconds' => $durationSeconds,
            'analysis_version' => 'v1-placeholder',
            'score_overall' => $scores['score_overall'],
            'score_pitch' => $scores['score_pitch'],
            'score_rhythm' => $scores['score_rhythm'],
            'likes_count' => 0,
            'views_count' => 0,
            'is_public' => $isPublic,
        ])->load(['song', 'user', 'mediaAsset']);

        $mediaAsset->update([
            'status' => 'queued',
            'metadata' => array_merge($mediaAsset->metadata ?? [], [
                'queued_for_processing_at' => now()->toISOString(),
            ]),
        ]);

        PerformanceUploaded::dispatch($performance);
        Queue::connection('redis')->push(new ProcessUploadedAudioJob($mediaAsset->id, $performance->id));

        return $performance;
    }
}
