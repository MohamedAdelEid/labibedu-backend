<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'category_id');
    }
}

