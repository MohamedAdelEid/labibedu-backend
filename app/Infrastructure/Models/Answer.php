<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Answer extends Model
{
    protected $fillable = [
        'student_id',
        'question_id',
        'user_answer',
        'gained_xp',
        'gained_coins',
        'submitted_at',
    ];

    public function getIsCorrectAttribute(): bool
    {
        $question = $this->question;

        // For written questions, correctness must be manually graded
        if ($question->type->value === 'written') {
            return false; // Default to false until manually graded
        }

        // For other types, check if all selections are correct
        $selections = $this->selections;

        if ($selections->isEmpty()) {
            return false;
        }

        return $selections->every(fn($selection) => $selection->is_correct);
    }

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selections(): HasMany
    {
        return $this->hasMany(AnswerSelection::class);
    }
}