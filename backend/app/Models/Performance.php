<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Performance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'song_id',
        'media_asset_id',
        'audio_url',
        'status',
        'visibility',
        'published_at',
        'processing_started_at',
        'processing_finished_at',
        'processing_error',
        'analysis_version',
        'duration_seconds',
        'score_overall',
        'score_pitch',
        'score_rhythm',
        'likes_count',
        'views_count',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'processing_started_at' => 'datetime',
            'processing_finished_at' => 'datetime',
            'duration_seconds' => 'integer',
            'score_overall' => 'integer',
            'score_pitch' => 'integer',
            'score_rhythm' => 'integer',
            'likes_count' => 'integer',
            'views_count' => 'integer',
            'is_public' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function mediaAsset()
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function vocalAnalyses()
    {
        return $this->hasMany(VocalAnalysis::class);
    }

    public function latestVocalAnalysis()
    {
        return $this->hasOne(VocalAnalysis::class)->latestOfMany();
    }
}
