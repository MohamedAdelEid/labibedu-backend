<?php

namespace App\Infrastructure\Models;

use App\Domain\Enums\ExamTrainingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ExamTraining extends Model
{
    use HasFactory;

    protected $table = 'exams_trainings';

    protected $fillable = [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'type',
        'start_date',
        'end_date',
        'duration',
        'created_by',
        'subject_id',
        'group_id',
    ];

    protected $casts = [
        'type' => ExamTrainingType::class,
        'duration' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function book(): HasMany
    {
        return $this->hasMany(Book::class, 'related_training_id');
    }

    public function video(): HasMany
    {
        return $this->hasMany(Video::class, 'related_training_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'train_id');
    }

    public function isExam(): bool
    {
        return $this->type === ExamTrainingType::EXAM;
    }

    public function isTraining(): bool
    {
        return $this->type === ExamTrainingType::TRAINING;
    }

    public function hasEnded(): bool
    {
        return $this->end_date && now()->isAfter($this->end_date);
    }

    public function hasStarted(): bool
    {
        return !$this->start_date || now()->isAfter($this->start_date);
    }

    public function getTotalXp(): int
    {
        return $this->questions()->sum('xp');
    }

    public function getTotalCoins(): int
    {
        return $this->questions()->sum('coins');
    }

    public function getTotalMarks(): int
    {
        return $this->questions()->sum('marks');
    }

    public function getQuestionsCountAttribute(): int
    {
        if ($this->relationLoaded('questions')) {
            return $this->questions->count();
        }

        return $this->questions()->count();
    }

    public function getSourceType(): ?string
    {
        // First: Check if examTraining is directly in journey_stage_contents
        $isInJourney = DB::table('journey_stage_contents')
            ->where('content_type', 'examTraining')
            ->where('content_id', $this->id)
            ->exists();

        if ($isInJourney) {
            return 'journey';
        }

        // Second: If not directly in journey, check if related book is in journey
        $relatedBook = DB::table('books')
            ->where('related_training_id', $this->id)
            ->first();

        if ($relatedBook) {
            $bookInJourney = DB::table('journey_stage_contents')
                ->where('content_type', 'book')
                ->where('content_id', $relatedBook->id)
                ->exists();

            $isBookInAssignment = DB::table('assignments')
                ->where('assignable_type', 'book')
                ->where('assignable_id', $relatedBook->id)
                ->exists();

            if ($isBookInAssignment) {
                return 'assignment';
            }

            if ($bookInJourney) {
                return 'journey';
            }
        }

        $relatedVideo = DB::table('videos')
            ->where('related_training_id', $this->id)
            ->first();

        if ($relatedVideo) {
            $videoInJourney = DB::table('journey_stage_contents')
                ->where('content_type', 'video')
                ->where('content_id', $relatedVideo->id)
                ->exists();

            $isVideoInAssignment = DB::table('assignments')
                ->where('assignable_type', 'video')
                ->where('assignable_id', $relatedVideo->id)
                ->exists();

            if ($isVideoInAssignment) {
                return 'assignment';
            }

            if ($videoInJourney) {
                return 'journey';
            }
        }

        // Third: Check lessons
        $hasLessons = $this->lessons()->exists();
        if ($hasLessons) {
            return 'lessons';
        }

        // Fourth: Check library
        $isInLibrary = DB::table('books')
            ->where('related_training_id', $this->id)
            ->where('is_in_library', true)
            ->exists();

        if ($isInLibrary) {
            return 'library';
        }

        // Fifth: Check assignment
        $isInAssignment = DB::table('assignments')
            ->where('assignable_type', 'examTraining')
            ->where('assignable_id', $this->id)
            ->exists();

        if ($isInAssignment) {
            return 'assignment';
        }

        return null;
    }
}
