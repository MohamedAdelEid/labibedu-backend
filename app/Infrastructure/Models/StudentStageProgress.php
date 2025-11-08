<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\StudentStageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentStageProgress extends Model
{
    use HasFactory;

    protected $table = 'student_journey_progress';

    protected $fillable = [
        'student_id',
        'stage_id',
        'earned_stars',
        'status',
    ];

    protected $casts = [
        'earned_stars' => 'integer',
        'status' => StudentStageStatus::class,
    ];

    /**
     * Get the student this progress belongs to
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the stage this progress is for
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(JourneyStage::class, 'stage_id');
    }
}

