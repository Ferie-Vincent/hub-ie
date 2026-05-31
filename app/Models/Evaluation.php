<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable = [
        'application_id',
        'evaluator_id',
        'score_profile',
        'score_motivation',
        'score_relevance',
        'score_representativity',
        'score_balance',
        'weighted_score',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'score_profile' => 'integer',
            'score_motivation' => 'integer',
            'score_relevance' => 'integer',
            'score_representativity' => 'integer',
            'score_balance' => 'integer',
            'weighted_score' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
