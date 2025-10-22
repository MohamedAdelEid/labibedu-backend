<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\ActivityType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_start',
        'session_end',
        'duration_seconds',
        'ip_address',
        'user_agent',
        'endpoint',
        'activity_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'session_start' => 'datetime',
            'session_end' => 'datetime',
            'activity_type' => ActivityType::class,
        ];
    }

    /**
     * Get the user that owns the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the duration in minutes.
     */
    public function getDurationMinutesAttribute(): float
    {
        return round($this->duration_seconds / 60, 2);
    }

    /**
     * Get the duration in hours.
     */
    public function getDurationHoursAttribute(): float
    {
        return round($this->duration_seconds / 3600, 2);
    }

    /**
     * Calculate duration from session start and end times.
     */
    public function calculateDuration(): void
    {
        if ($this->session_start && $this->session_end) {
            $this->duration_seconds = $this->session_end->diffInSeconds($this->session_start);
        }
    }
}
