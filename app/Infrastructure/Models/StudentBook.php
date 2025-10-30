<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'book_id',
        'last_read_page_id',
        'is_favorite',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function lastReadPage(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'last_read_page_id');
    }
}

