<?php

namespace App\Services\VocalAnalysis;

use App\Models\Performance;
use App\Models\VocalAnalysis;

class VocalAnalysisService
{
    public function createPlaceholderAnalysis(Performance $performance): VocalAnalysis
    {
        return VocalAnalysis::updateOrCreate(
            [
                'performance_id' => $performance->id,
                'analysis_version' => 'v1-placeholder',
            ],
            [
                'media_asset_id' => $performance->media_asset_id,
                'status' => 'completed',
                'model_name' => 'temporary-score-generator',
                'model_version' => 'foundation',
                'analysis_started_at' => now(),
                'analysis_finished_at' => now(),
                'pitch_accuracy' => $performance->score_pitch,
                'rhythm_accuracy' => $performance->score_rhythm,
                'confidence_score' => 0,
                'raw_analysis' => [
                    'note' => 'Placeholder analysis. Real vocal AI will populate detailed metrics later.',
                    'score_overall' => $performance->score_overall,
                    'score_pitch' => $performance->score_pitch,
                    'score_rhythm' => $performance->score_rhythm,
                ],
            ],
        );
    }
}
