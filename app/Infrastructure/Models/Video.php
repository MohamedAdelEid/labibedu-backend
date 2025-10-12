<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    protected $fillable = [
        'title',
        'url',
        'duration',
        'start_date',
        'end_date',
        'xp',
        'coins',
        'marks',
        'subject_id',
        'related_training_id',
    ];

    protected $casts = [
        'duration' => 'integer',
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

    public function watchProgress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }
}