<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JourneyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get localized name based on current app locale
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_en;
    }

    /**
     * Get all stages for this level
     */
    public function stages(): HasMany
    {
        return $this->hasMany(JourneyStage::class, 'level_id')->orderBy('order');
    }
}

