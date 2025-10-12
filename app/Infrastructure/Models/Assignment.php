<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'exam_training_id',
        'video_id',
        'book_id',
        'teacher_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function examTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'assignment_student')
            ->withPivot('status', 'assigned_at')
            ->withTimestamps();
    }
}
