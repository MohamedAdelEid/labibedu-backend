<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\QuestionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'exam_training_id',
        'title',
        'type',
        'xp',
        'coins',
    ];

    protected $casts = [
        'type' => QuestionType::class,
    ];

    public function examTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class, 'exam_training_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }
}