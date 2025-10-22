<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avatar extends Model
{
    protected $fillable = [
        'url',
        'coins',
    ];

    protected $casts = [
        'coins' => 'integer',
    ];

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
}
