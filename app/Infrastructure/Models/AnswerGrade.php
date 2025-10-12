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
}
