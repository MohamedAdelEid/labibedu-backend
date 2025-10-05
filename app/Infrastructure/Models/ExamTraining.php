<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\ExamTrainingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ExamTraining extends Model
{
    protected $table = 'exams_trainings';

    protected $fillable = [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'type',
        'duration',
        'start_date',
        'end_date',
        'locked_after_duration',
        'created_by',
        'subject_id',
        'video_id',
        'book_id',
        'group_id',
    ];

    protected $casts = [
        'type' => ExamTrainingType::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'locked_after_duration' => 'date',
    ];

    public function isLocked(): bool
    {
        if ($this->locked_after_duration && Carbon::now()->isAfter($this->locked_after_duration)) {
            return true;
        }

        if ($this->end_date && Carbon::now()->isAfter($this->end_date)) {
            return true;
        }

        return false;
    }

    public function isAvailable(): bool
    {
        if ($this->start_date && Carbon::now()->isBefore($this->start_date)) {
            return false;
        }

        return !$this->isLocked();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'created_by');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'exam_training_id');
    }
}