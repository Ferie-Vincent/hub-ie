<?php

namespace App\Observers;

use App\Models\Evaluation;

class EvaluationObserver
{
    public function saving(Evaluation $evaluation): void
    {
        $evaluation->weighted_score = round(
            $evaluation->score_profile * 0.25
            + $evaluation->score_motivation * 0.25
            + $evaluation->score_relevance * 0.20
            + $evaluation->score_representativity * 0.15
            + $evaluation->score_balance * 0.15,
            2
        );
    }

    public function saved(Evaluation $evaluation): void
    {
        $this->syncApplicationScore($evaluation);
    }

    public function deleted(Evaluation $evaluation): void
    {
        $this->syncApplicationScore($evaluation);
    }

    private function syncApplicationScore(Evaluation $evaluation): void
    {
        $stats = Evaluation::where('application_id', $evaluation->application_id)
            ->selectRaw('COUNT(*) as cnt, AVG(weighted_score) as avg')
            ->first();

        $evaluation->application()->update([
            'evaluations_count' => $stats->cnt ?? 0,
            'average_score' => $stats->avg,
        ]);
    }
}
