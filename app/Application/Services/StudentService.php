<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Services\StudentServiceInterface;
use App\Domain\Interfaces\Repositories\StudentRepositoryInterface;
use App\Domain\Interfaces\Services\UserActivityServiceInterface;
use App\Domain\Interfaces\Services\QuestionServiceInterface;
use App\Domain\Interfaces\Services\BookServiceInterface;
use App\Domain\Interfaces\Services\AvatarServiceInterface;
use App\Application\Exceptions\Student\StudentNotFoundException;
use App\Infrastructure\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentService implements StudentServiceInterface
{
    public function __construct(
        private StudentRepositoryInterface $studentRepository,
        private UserActivityServiceInterface $userActivityService,
        private QuestionServiceInterface $questionService,
        private BookServiceInterface $bookService,
        private AvatarServiceInterface $avatarService
    ) {
    }

    /**
     * Update student's XP and coins
     */
    public function updateStudentScoring(int $studentId, int $xpGained, int $coinsGained, string $reason = ''): array
    {
        return DB::transaction(function () use ($studentId, $xpGained, $coinsGained, $reason) {
            $success = $this->studentRepository->updateScoring($studentId, $xpGained, $coinsGained);

            if (!$success) {
                throw new \Exception('Student not found');
            }

            // Log the scoring update
            Log::info('Student scoring updated', [
                'student_id' => $studentId,
                'xp_gained' => $xpGained,
                'coins_gained' => $coinsGained,
                'reason' => $reason,
            ]);

            return [
                'success' => true,
                'xp_gained' => $xpGained,
                'coins_gained' => $coinsGained,
                'reason' => $reason,
            ];
        });
    }

    /**
     * Get student's complete scoring summary
     */
    public function getStudentScoringSummary(Student $student): array
    {
        $totalScoring = $this->studentRepository->getTotalScoring($student->id);

        return [
            'current_totals' => [
                'xp' => $student->xp,
                'coins' => $student->coins,
            ],
            'calculated_totals' => $totalScoring,
            'level_progress' => $student->getXpProgress(),
        ];
    }

    /**
     * Recalculate student's total XP and coins from all activities
     */
    public function recalculateStudentTotals(Student $student): array
    {
        $totals = $this->studentRepository->getTotalScoring($student->id);

        $student->update([
            'xp' => $totals['total_xp'],
            'coins' => $totals['total_coins'],
        ]);

        return $totals;
    }

    /**
     * Award bonus points for achievements
     */
    public function awardBonus(Student $student, int $xpBonus, int $coinsBonus, string $reason): array
    {
        return $this->updateStudentScoring($student->id, $xpBonus, $coinsBonus, $reason);
    }

    /**
     * Update student profile information
     */
    public function updateStudentProfile(Student $student, array $data): Student
    {
        $allowedFields = ['date_of_birth', 'school_id', 'classroom_id', 'group_id'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));

        $student->update($updateData);

        Log::info('Student profile updated', [
            'student_id' => $student->id,
            'updated_fields' => array_keys($updateData),
        ]);

        return $student->fresh();
    }

    /**
     * Get complete student profile data
     */
    public function getStudentProfile(int $studentId): array
    {
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new \Exception('Student not found');
        }

        // Get avatar info from AvatarService
        $avatarData = $this->avatarService->getActiveAvatarInfo($student->id);

        // Get total time spent from UserActivityService
        $timeData = $this->userActivityService->getTotalTimeSpent($student->user_id)['total_seconds'];

        // Get question statistics from QuestionService
        $questionStats = $this->questionService->getStudentQuestionStatistics($student->id);

        // Get books read count from BookService
        $booksRead = $this->bookService->getBooksReadCount($student->id);

        // Return raw data for the resource to format
        return [
            'student' => $student,
            'avatar' => $avatarData,
            'total_time_spent' => $timeData,
            'question_stats' => $questionStats,
            'books_read' => $booksRead,
        ];
    }

    /**
     * Complete first-time student setup
     */
    public function completeFirstSetup(int $studentId, array $data): array
    {
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new StudentNotFoundException("Student with ID {$studentId} not found");
        }

        // Check if this is actually the first time
        if (!$student->is_first_time) {
            throw new \Exception("Student has already completed first-time setup");
        }

        // Automatically set theme based on gender
        $theme = $data['gender'] === 'male' ? 'blue' : 'pink';

        $updatedStudent = $this->studentRepository->updateFirstSetup($studentId, [
            'name' => $data['name'],
            'age_group_id' => $data['age_group_id'],
            'gender' => $data['gender'],
            'theme' => $theme,
        ]);

        return [
            'message' => 'First-time setup completed successfully',
            'student' => $updatedStudent,
        ];
    }

    /**
     * Get student settings
     */
    public function getSettings(int $studentId): array
    {
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new StudentNotFoundException("Student with ID {$studentId} not found");
        }

        return [
            'language' => $student->language?->value,
            'theme' => $student->theme?->value,
            'notifications_enabled' => $student->notifications_enabled,
            'haptic_feedback_enabled' => $student->haptic_feedback_enabled,
            'is_first_time' => $student->is_first_time,
        ];
    }

    /**
     * Update student settings
     */
    public function updateSettings(int $studentId, array $data): array
    {
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new StudentNotFoundException("Student with ID {$studentId} not found");
        }

        $dataToUpdate = array_filter($data, fn($value) => $value !== null);

        $updatedStudent = $this->studentRepository->updateSettings($studentId, $dataToUpdate);

        return [
            'message' => 'Settings updated successfully',
            'student' => $updatedStudent,
        ];
    }
}
