<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerOrder extends Model
{
    protected $fillable = [
        'answer_id',
        'option_id',
        'order',
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
