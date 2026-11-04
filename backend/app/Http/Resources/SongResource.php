<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'artist' => $this->artist,
            'genre' => $this->genre,
            'language' => $this->language,
            'key' => $this->key,
            'musical_key' => $this->musical_key,
            'bpm' => $this->bpm,
            'difficulty_level' => $this->difficulty_level,
            'audio_url' => $this->audio_url,
            'cover_image_url' => $this->cover_image_url,
            'source_type' => $this->source_type,
            'license_type' => $this->license_type,
            'available_countries' => $this->available_countries,
            'vocal_range_min' => $this->vocal_range_min,
            'vocal_range_max' => $this->vocal_range_max,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
