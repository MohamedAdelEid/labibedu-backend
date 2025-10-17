<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\AttemptStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_training_id',
        'start_time',
        'end_time',
        'remaining_seconds',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'remaining_seconds' => 'integer',
        'status' => AttemptStatus::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function examTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class);
    }

    public function isFinished(): bool
    {
        return $this->status === AttemptStatus::FINISHED;
    }

    public function isInProgress(): bool
    {
        return $this->status === AttemptStatus::IN_PROGRESS;
    }

    public function hasExpired(): bool
    {
        // For training (duration = 0), never expire
        if ($this->remaining_seconds === 0) {
            return false;
        }

        return $this->remaining_seconds <= 0;
    }

    public function markAsFinished(): void
    {
        $this->update([
            'status' => AttemptStatus::FINISHED,
            'end_time' => now(),
        ]);
    }

    public function updateRemainingTime(int $timeSpent): void
    {
        // Security check: time_spent cannot be greater than current remaining_seconds
        if ($timeSpent > $this->remaining_seconds) {
            throw new \Exception('Invalid time_spent value. Cannot spend more time than remaining.');
        }

        // Check if time_spent is negative or zero
        if ($timeSpent <= 0) {
            throw new \Exception('time_spent must be greater than 0.');
        }

        $newRemaining = max(0, $this->remaining_seconds - $timeSpent);
        $this->update(['remaining_seconds' => $newRemaining]);
    }
}
