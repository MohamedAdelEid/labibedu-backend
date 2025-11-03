<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'text',
        'image',
        'mp3',
        'is_text_to_speech',
    ];

    protected $casts = [
        'is_text_to_speech' => 'boolean',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function getImageAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        return config('app.url') . '/storage/' . $value;
    }

    public function getMp3Attribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        return config('app.url') . '/storage/' . $value;
    }
}

