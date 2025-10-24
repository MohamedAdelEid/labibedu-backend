<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avatar extends Model
{
    protected $fillable = [
        'url',
        'coins',
        'category_id',
    ];

    protected $casts = [
        'coins' => 'integer',
        'category_id' => 'integer',
    ];

    /**
     * Get the category this avatar belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AvatarCategory::class, 'category_id');
    }

    /**
     * Get the students that own this avatar
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'avatar_student')
            ->withPivot('purchased_at')
            ->withTimestamps();
    }

    /**
     * Get students who have this avatar as their active avatar
     */
    public function activeStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'active_avatar_id');
    }

    /**
     * Get the full URL for the avatar
     */
    public function getFullUrlAttribute(): string
    {
        if (empty($this->url)) {
            return '';
        }

        // Use the public storage URL
        return asset('assets/images/avatars/' . $this->url);
    }
}
