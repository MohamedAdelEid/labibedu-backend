<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'classroom_id'];

    /**
     * Get the localized name based on the current app locale
     * Note: Subject only has a 'name' field, not separate ar/en fields
     */
    public function getNameAttribute($value): string
    {
        return $value;
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
}