<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerSelection extends Model
{
    protected $fillable = [
        'answer_id',
        'option_id',
        'is_correct',
        'gained_xp',
        'gained_coins',
        'order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }
}