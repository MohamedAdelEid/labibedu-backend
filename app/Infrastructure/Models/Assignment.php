<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'assignable_type',
        'assignable_id',
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

    /**
     * Get the parent assignable model (ExamTraining, Video, or Book).
     */
    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Helper methods for backward compatibility
     */
    public function examTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class, 'assignable_id')
            ->where('assignable_type', ExamTraining::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'assignable_id')
            ->where('assignable_type', Video::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'assignable_id')
            ->where('assignable_type', Book::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'assignment_student')
            ->withPivot('status', 'assigned_at')
            ->withTimestamps();
    }
}
