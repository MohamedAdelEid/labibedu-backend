<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'category',
        'grade_id',
        'subject_id',
    ];

    protected $casts = [
        'grade_id' => 'integer',
        'subject_id' => 'integer',
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
     * Get the localized name (alias for title for consistency)
     */
    public function getNameAttribute(): string
    {
        return $this->getTitleAttribute();
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'lesson_book')
            ->withTimestamps();
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'lesson_video')
            ->withTimestamps();
    }
}
