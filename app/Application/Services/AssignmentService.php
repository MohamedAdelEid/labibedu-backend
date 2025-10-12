<?php

namespace App\Application\Services;

use App\Application\Calculators\ExamPerformanceCalculator;
use App\Application\Calculators\VideoPerformanceCalculator;
use App\Application\Calculators\BookPerformanceCalculator;
use App\Domain\Interfaces\Repositories\AssignmentRepositoryInterface;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Domain\Interfaces\Repositories\VideoRepositoryInterface;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssignmentService
{
    public function __construct(
        private AssignmentRepositoryInterface $assignmentRepository,
        private AnswerRepositoryInterface $answerRepository,
        private VideoRepositoryInterface $videoRepository,
        private BookRepositoryInterface $bookRepository,
        private ExamPerformanceCalculator $examPerformanceCalculator,
        private VideoPerformanceCalculator $videoPerformanceCalculator,
        private BookPerformanceCalculator $bookPerformanceCalculator
    ) {}

    /**
     * Get assignments for student - returns raw data for Resources to format
     */
    public function getAssignments(int $studentId, ?string $type, ?string $status, int $perPage): LengthAwarePaginator
    {
        return $this->assignmentRepository->getAssignmentsForStudent($studentId, $type, $status, $perPage);
    }

    /**
     * Get exam/training performance
     */
    public function getExamPerformance(int $studentId, int $examTrainingId): array
    {
        $examTraining = $this->assignmentRepository->findAssignmentForStudent($examTrainingId, $studentId);
        $questions = $examTraining->questions;
        $answers = $this->answerRepository->getAnswersForStudentExam($studentId, $examTrainingId);

        return $this->examPerformanceCalculator->calculate($questions, $answers);
    }

    /**
     * Get video performance
     */
    public function getVideoPerformance(int $studentId, int $videoId, ?array $relatedTrainingPerformance = null): array
    {
        $video = $this->videoRepository->findOrFail($videoId);
        $progress = $this->videoRepository->getProgress($studentId, $videoId);

        return $this->videoPerformanceCalculator->calculate($video, $progress, $relatedTrainingPerformance);
    }

    /**
     * Get book performance
     */
    public function getBookPerformance(int $studentId, int $bookId, ?array $relatedTrainingPerformance = null): array
    {
        $book = $this->bookRepository->findOrFail($bookId);
        $progress = $this->bookRepository->getProgress($studentId, $bookId);

        return $this->bookPerformanceCalculator->calculate($book, $progress, $relatedTrainingPerformance);
    }
}
