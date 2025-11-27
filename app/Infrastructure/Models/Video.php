<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'url',
        'cover',
        'duration',
        'xp',
        'coins',
        'marks',
        'subject_id',
        'related_training_id',
    ];

    protected $casts = [
        'duration' => 'integer',
        'xp' => 'integer',
        'coins' => 'integer',
        'marks' => 'integer',
    ];

    /**
     * Get the localized title based on the current app locale
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->attributes['title_' . $locale] ?? $this->attributes['title_ar'] ?? '';
    }

    /**
     * Get the cover URL
     */
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

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function relatedTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class, 'related_training_id');
    }

    public function watchProgress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_video')
            ->withTimestamps();
    }
}