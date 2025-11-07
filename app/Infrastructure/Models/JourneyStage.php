<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JourneyStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'type',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the level this stage belongs to
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(JourneyLevel::class, 'level_id');
    }

    /**
     * Get all content items for this stage
     */
    public function contents(): HasMany
    {
        return $this->hasMany(StageContent::class, 'stage_id');
    }

    /**
     * Get student progress for this stage
     */
    public function studentProgress(): HasMany
    {
        return $this->hasMany(StudentStageProgress::class, 'stage_id');
    }
}

