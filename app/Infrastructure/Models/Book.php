<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover',
        'thumbnail',
        'is_in_library',
        'language',
        'has_sound',
        'xp',
        'coins',
        'marks',
        'subject_id',
        'related_training_id',
        'level_id',
    ];

    protected $casts = [
        'is_in_library' => 'boolean',
        'has_sound' => 'boolean',
        'xp' => 'integer',
        'coins' => 'integer',
        'marks' => 'integer',
    ];

    public function getCoverAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        // If already a full URL, return as is
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // Prepend storage URL
        return config('app.url') . '/storage/' . $value;
    }

    public function getThumbnailAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        // If already a full URL, return as is
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // Prepend storage URL
        return config('app.url') . '/storage/' . $value;
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function relatedTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class, 'related_training_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function studentBooks(): HasMany
    {
        return $this->hasMany(StudentBook::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_books')
            ->withPivot('is_favorite', 'last_read_page_id')
            ->withTimestamps();
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_book')
            ->withTimestamps();
    }
}
