<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer_id',
        'graded_by',
        'is_correct',
        'gained_xp',
        'gained_coins',
        'gained_marks',
        'feedback',
        'status',
        'graded_at',
    ];

    protected $casts = [
        'gained_xp' => 'integer',
        'gained_coins' => 'integer',
        'gained_marks' => 'integer',
        'is_correct' => 'boolean',
        'graded_at' => 'datetime',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'graded_by');
    }

    /**
     * Get total points earned from this grade
     */
    public function getTotalPoints(): int
    {
        return $this->gained_xp + $this->gained_coins;
    }

    /**
     * Check if this grade awarded any points
     */
    public function hasPoints(): bool
    {
        return $this->gained_xp > 0 || $this->gained_coins > 0;
    }

    /**
     * Get scoring summary
     */
    public function getScoringSummary(): array
    {
        return [
            'is_correct' => $this->is_correct,
            'gained_xp' => $this->gained_xp,
            'gained_coins' => $this->gained_coins,
            'total_points' => $this->getTotalPoints(),
            'has_points' => $this->hasPoints(),
            'graded_at' => $this->graded_at,
            'status' => $this->status,
        ];
    }
}
