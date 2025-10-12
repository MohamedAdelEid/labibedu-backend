<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookProgress extends Model
{
    use HasFactory;

    protected $table = 'book_progress';

    protected $fillable = [
        'student_id',
        'book_id',
        'pages_read',
        'is_completed',
        'earned_xp',
        'earned_coins',
        'earned_marks',
    ];

    protected $casts = [
        'pages_read' => 'integer',
        'is_completed' => 'boolean',
        'earned_xp' => 'integer',
        'earned_coins' => 'integer',
        'earned_marks' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
