<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerPair extends Model
{
    protected $fillable = [
        'answer_id',
        'left_option_id',
        'right_option_id',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function leftOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'left_option_id');
    }

    public function rightOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'right_option_id');
    }
}
