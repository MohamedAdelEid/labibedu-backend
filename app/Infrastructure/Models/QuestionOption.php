<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'text',
        'image',
        'is_correct',
        'side',
        'match_id',
        'arrange_order',
        'xp',
        'coins',
        'marks',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'arrange_order' => 'integer',
        'xp' => 'integer',
        'coins' => 'integer',
        'marks' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function matchOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'match_id');
    }
}
