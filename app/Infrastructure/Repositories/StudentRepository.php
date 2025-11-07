<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\StudentRepositoryInterface;
use App\Infrastructure\Models\Student;
use App\Infrastructure\Models\AnswerGrade;
use App\Infrastructure\Models\VideoProgress;
use Illuminate\Support\Collection;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    /**
     * Find student by ID
     */
    public function findById(int $id): ?Student
    {
        return $this->model->find($id);
    }

    /**
     * Find student by user ID
     */
    public function findByUserId(int $userId): ?Student
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Update student XP and coins
     */
    public function updateScoring(int $studentId, int $xpGained, int $coinsGained): bool
    {
        $student = $this->findById($studentId);
        if (!$student) {
            return false;
        }
        
        $student->increment('xp', $xpGained);
        $student->increment('coins', $coinsGained);

        return true;
    }

    /**
     * Get student's total XP and coins from all sources
     */
    public function getTotalScoring(int $studentId): array
    {
        // Calculate from answer grades
        $answerGrades = AnswerGrade::whereHas('answer', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->get();

        $totalXp = $answerGrades->sum('gained_xp');
        $totalCoins = $answerGrades->sum('gained_coins');

        // Add video progress earnings
        $videoProgress = VideoProgress::where('student_id', $studentId)->get();
        $totalXp += $videoProgress->sum('earned_xp');
        $totalCoins += $videoProgress->sum('earned_coins');

        // Note: Book earnings are tracked through answer grades (book-related trainings)
        // Books themselves don't store separate earned_xp/earned_coins in the database

        return [
            'total_xp' => $totalXp,
            'total_coins' => $totalCoins,
        ];
    }

    /**
     * Get students by school
     */
    public function getBySchool(int $schoolId): Collection
    {
        return $this->model->where('school_id', $schoolId)->get();
    }

    /**
     * Get students by classroom
     */
    public function getByClassroom(int $classroomId): Collection
    {
        return $this->model->where('classroom_id', $classroomId)->get();
    }

    /**
     * Get students by group
     */
    public function getByGroup(int $groupId): Collection
    {
        return $this->model->where('group_id', $groupId)->get();
    }
}
