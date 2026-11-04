<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocalAnalysis extends Model
{
    protected $fillable = [
        'performance_id',
        'media_asset_id',
        'status',
        'model_name',
        'model_version',
        'analysis_version',
        'analysis_started_at',
        'analysis_finished_at',
        'audio_duration_ms',
        'sample_rate',
        'channels',
        'bitrate',
        'format',
        'pitch_accuracy',
        'rhythm_accuracy',
        'timing_offset_ms',
        'vibrato_score',
        'breath_control_score',
        'stability_score',
        'range_score',
        'pronunciation_score',
        'energy_score',
        'noise_level',
        'clipping_detected',
        'silence_ratio',
        'confidence_score',
        'raw_analysis',
        'features_storage_key',
    ];

    protected function casts(): array
    {
        return [
            'analysis_started_at' => 'datetime',
            'analysis_finished_at' => 'datetime',
            'raw_analysis' => 'array',
            'clipping_detected' => 'boolean',
        ];
    }

    public function performance()
    {
        return $this->belongsTo(Performance::class);
    }

    public function mediaAsset()
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function segments()
    {
        return $this->hasMany(VocalAnalysisSegment::class);
    }
}
