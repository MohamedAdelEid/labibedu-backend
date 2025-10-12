<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoProgress extends Model
{
    use HasFactory;

    protected $table = 'video_progress';

    protected $fillable = [
        'student_id',
        'video_id',
        'watched_duration',
        'is_completed',
        'earned_xp',
        'earned_coins',
        'earned_marks',
    ];

    protected $casts = [
        'watched_duration' => 'integer',
        'is_completed' => 'boolean',
        'earned_xp' => 'integer',
        'earned_coins' => 'integer',
        'earned_marks' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
