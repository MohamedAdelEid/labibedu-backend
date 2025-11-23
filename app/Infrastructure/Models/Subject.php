<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name_ar', 'name_en', 'classroom_id'];

    /**
     * Get the localized name based on the current app locale
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->attributes['name_' . $locale] ?? $this->attributes['name_ar'] ?? '';
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function examsTrainings(): HasMany
    {
        return $this->hasMany(ExamTraining::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}