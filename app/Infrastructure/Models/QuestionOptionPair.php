<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOptionPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'left_option_id',
        'right_option_id',
        'xp',
        'coins',
        'marks',
    ];

    protected $casts = [
        'xp' => 'integer',
        'coins' => 'integer',
        'marks' => 'integer',
    ];

    public function leftOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'left_option_id');
    }

    public function rightOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'right_option_id');
    }
}
