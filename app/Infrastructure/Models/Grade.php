<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = ['name', 'level'];

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }
}