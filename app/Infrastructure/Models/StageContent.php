<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StageContent extends Model
{
    use HasFactory;

    protected $table = 'journey_stage_contents';

    protected $fillable = [
        'stage_id',
        'content_type',
        'content_id',
    ];

    /**
     * Get the stage this content belongs to
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(JourneyStage::class, 'stage_id');
    }

    /**
     * Get the content model (Book, Video, or ExamTraining)
     */
    public function getContentModel()
    {
        return match ($this->content_type) {
            'book' => Book::find($this->content_id),
            'video' => Video::find($this->content_id),
            'examTraining' => ExamTraining::find($this->content_id),
            default => null,
        };
    }
}

