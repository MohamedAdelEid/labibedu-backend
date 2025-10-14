<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Answer extends Model
{
    protected $fillable = [
        'student_id',
        'question_id',
        'option_id',
        'true_false_answer',
        'user_answer',
        'submitted_at',
    ];

    protected $casts = [
        'true_false_answer' => 'boolean',
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

    public function option(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }

    public function pairs(): HasMany
    {
        return $this->hasMany(AnswerPair::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(AnswerOrder::class);
    }

    public function grade(): HasOne
    {
        return $this->hasOne(AnswerGrade::class);
    }
}
