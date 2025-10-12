<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'question_id',
        'user_answer',
        'gained_xp',
        'gained_coins',
        'submitted_at',
    ];

    protected $casts = [
        'gained_xp' => 'integer',
        'gained_coins' => 'integer',
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

    public function grade(): HasOne
    {
        return $this->hasOne(AnswerGrade::class);
    }
}
