<?php

namespace App\Services\VocalAnalysis;

class TemporaryScoringService
{
    /**
     * Placeholder scoring until real vocal AI is introduced.
     *
     * @return array{score_overall:int,score_pitch:int,score_rhythm:int}
     */
    public function generate(): array
    {
        return [
            'score_overall' => random_int(60, 95),
            'score_pitch' => random_int(60, 95),
            'score_rhythm' => random_int(60, 95),
        ];
    }
}
