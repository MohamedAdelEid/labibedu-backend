<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerSelection extends Model
{
    use HasFactory;

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
        'gained_xp' => 'integer',
        'gained_coins' => 'integer',
        'order' => 'integer',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
