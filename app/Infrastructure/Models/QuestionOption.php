<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    protected $fillable = [
        'question_id',
        'text',
        'image',
        'is_correct',
        'side',
        'match_id',
        'arrange_order',
        'xp',
        'coins',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function matchOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'match_id');
    }
}