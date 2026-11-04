<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocalAnalysisSegment extends Model
{
    protected $fillable = [
        'vocal_analysis_id',
        'start_ms',
        'end_ms',
        'expected_pitch',
        'detected_pitch',
        'pitch_error_cents',
        'timing_error_ms',
        'segment_score',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function vocalAnalysis()
    {
        return $this->belongsTo(VocalAnalysis::class);
    }
}
