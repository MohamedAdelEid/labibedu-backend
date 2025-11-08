<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\Gender;
use App\Domain\Enums\Language;
use App\Domain\Enums\Theme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'age_group_id',
        'gender',
        'is_first_time',
        'language',
        'theme',
        'notifications_enabled',
        'haptic_feedback_enabled',
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
        'gender' => Gender::class,
        'language' => Language::class,
        'theme' => Theme::class,
        'is_first_time' => 'boolean',
        'notifications_enabled' => 'boolean',
        'haptic_feedback_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ageGroup(): BelongsTo
    {
        return $this->belongsTo(AgeGroup::class);
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

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'student_books')
            ->withPivot('is_favorite', 'last_read_page_id')
            ->withTimestamps();
    }

    public function studentBooks(): HasMany
    {
        return $this->hasMany(StudentBook::class);
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'student_level')
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