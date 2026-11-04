<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Song extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'artist',
        'genre',
        'language',
        'key',
        'musical_key',
        'bpm',
        'difficulty_level',
        'audio_url',
        'cover_image_url',
        'source_type',
        'license_type',
        'rights_holder',
        'available_countries',
        'lyrics',
        'lyrics_language',
        'vocal_range_min',
        'vocal_range_max',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'bpm' => 'integer',
            'difficulty_level' => 'integer',
            'available_countries' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function translations()
    {
        return $this->hasMany(SongTranslation::class);
    }
}
