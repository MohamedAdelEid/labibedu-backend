<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'xp',
        'coins',
        'date_of_birth',
        'school_id',
        'classroom_id',
        'group_id',
        'active_avatar_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'xp' => 'integer',
        'coins' => 'integer',
        'active_avatar_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function activeAvatar(): BelongsTo
    {
        return $this->belongsTo(Avatar::class, 'active_avatar_id');
    }

    public function avatars(): BelongsToMany
    {
        return $this->belongsToMany(Avatar::class, 'avatar_student')
            ->withPivot('purchased_at')
            ->withTimestamps();
    }

    /**
     * Get student's current level based on XP
     */
    public function getLevel(): int
    {
        // Simple level calculation: every 1000 XP = 1 level
        return (int) floor($this->xp / 1000) + 1;
    }

    /**
     * Get XP needed for next level
     */
    public function getXpForNextLevel(): int
    {
        $currentLevel = $this->getLevel();
        $nextLevelXp = $currentLevel * 1000;
        return $nextLevelXp - $this->xp;
    }

    /**
     * Get XP progress for current level
     */
    public function getXpProgress(): array
    {
        $currentLevel = $this->getLevel();
        $currentLevelXp = ($currentLevel - 1) * 1000;
        $nextLevelXp = $currentLevel * 1000;
        $progressXp = $this->xp - $currentLevelXp;
        $totalXpForLevel = $nextLevelXp - $currentLevelXp;

        return [
            'current_level' => $currentLevel,
            'current_xp' => $this->xp,
            'level_xp' => $progressXp,
            'total_xp_for_level' => $totalXpForLevel,
            'progress_percentage' => $totalXpForLevel > 0 ? round(($progressXp / $totalXpForLevel) * 100, 2) : 0,
        ];
    }

    /**
     * Check if student can afford something with coins
     */
    public function canAfford(int $cost): bool
    {
        return $this->coins >= $cost;
    }

    /**
     * Spend coins
     */
    public function spendCoins(int $amount): bool
    {
        if (!$this->canAfford($amount)) {
            return false;
        }

        $this->decrement('coins', $amount);
        return true;
    }

    /**
     * Add coins
     */
    public function addCoins(int $amount): void
    {
        $this->increment('coins', $amount);
    }

    /**
     * Add XP
     */
    public function addXp(int $amount): void
    {
        $this->increment('xp', $amount);
    }
}