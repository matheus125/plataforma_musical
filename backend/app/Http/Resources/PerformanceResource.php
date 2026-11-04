<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerformanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'audio_url' => $this->audio_url,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'published_at' => $this->published_at?->toISOString(),
            'analysis_version' => $this->analysis_version,
            'duration_seconds' => $this->duration_seconds,
            'score_overall' => $this->score_overall,
            'score_pitch' => $this->score_pitch,
            'score_rhythm' => $this->score_rhythm,
            'likes_count' => $this->likes_count,
            'views_count' => $this->views_count,
            'is_public' => $this->is_public,
            'song' => new SongResource($this->whenLoaded('song')),
            'user' => new UserResource($this->whenLoaded('user')),
            'media_asset' => $this->whenLoaded('mediaAsset', fn () => [
                'id' => $this->mediaAsset?->id,
                'type' => $this->mediaAsset?->type,
                'status' => $this->mediaAsset?->status,
                'mime_type' => $this->mediaAsset?->mime_type,
                'size_bytes' => $this->mediaAsset?->size_bytes,
                'public_url' => $this->mediaAsset?->public_url,
            ]),
            'latest_vocal_analysis' => $this->whenLoaded('latestVocalAnalysis', fn () => [
                'id' => $this->latestVocalAnalysis?->id,
                'status' => $this->latestVocalAnalysis?->status,
                'analysis_version' => $this->latestVocalAnalysis?->analysis_version,
                'model_name' => $this->latestVocalAnalysis?->model_name,
                'confidence_score' => $this->latestVocalAnalysis?->confidence_score,
            ]),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
