<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\QuestionLanguage;
use App\Domain\Enums\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_training_id',
        'title',
        'type',
        'language',
        'xp',
        'coins',
        'marks',
    ];

    protected $casts = [
        'type' => QuestionType::class,
        'language' => QuestionLanguage::class,
        'xp' => 'integer',
        'coins' => 'integer',
        'marks' => 'integer',
    ];

    public function examTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
