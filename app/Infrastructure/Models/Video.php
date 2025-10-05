<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = ['title', 'title_ar', 'subject_id', 'url'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}