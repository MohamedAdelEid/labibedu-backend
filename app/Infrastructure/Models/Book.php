<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'pages_count',
        'start_date',
        'end_date',
        'xp',
        'coins',
        'marks',
        'subject_id',
        'related_training_id',
    ];

    protected $casts = [
        'pages_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'xp' => 'integer',
        'coins' => 'integer',
        'marks' => 'integer',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function relatedTraining(): BelongsTo
    {
        return $this->belongsTo(ExamTraining::class, 'related_training_id');
    }

    public function readProgress(): HasMany
    {
        return $this->hasMany(BookProgress::class);
    }
}
