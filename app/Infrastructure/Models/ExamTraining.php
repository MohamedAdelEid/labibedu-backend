<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\ExamTrainingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamTraining extends Model
{
    use HasFactory;

    protected $table = 'exams_trainings';

    protected $fillable = [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'type',
        'start_date',
        'end_date',
        'duration',
        'created_by',
        'subject_id',
        'group_id',
    ];

    protected $casts = [
        'type' => ExamTrainingType::class,
        'duration' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function book(): HasMany
    {
        return $this->hasMany(Book::class, 'related_training_id');
    }

    public function isExam(): bool
    {
        return $this->type === ExamTrainingType::EXAM;
    }

    public function isTraining(): bool
    {
        return $this->type === ExamTrainingType::TRAINING;
    }

    public function hasEnded(): bool
    {
        return $this->end_date && now()->isAfter($this->end_date);
    }

    public function hasStarted(): bool
    {
        return !$this->start_date || now()->isAfter($this->start_date);
    }

    public function getTotalXp(): int
    {
        return $this->questions()->sum('xp');
    }

    public function getTotalCoins(): int
    {
        return $this->questions()->sum('coins');
    }

    public function getTotalMarks(): int
    {
        return $this->questions()->count();
    }
}
