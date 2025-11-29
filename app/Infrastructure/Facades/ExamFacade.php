<?php

namespace App\Infrastructure\Facades;

use App\Application\DTOs\Exam\SendHeartbeatDTO;
use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Application\DTOs\Exam\SubmitEntireExamDTO;
use App\Application\Services\ExamService;

/**
 * ExamFacade - Provides a simplified interface between Controller and Services
 * 
 * This facade acts as the entry point for all exam-related operations,
 * coordinating between multiple services while keeping the controller thin.
 */
class ExamFacade
{
    public function __construct(
        private ExamService $examService
    ) {
    }

    /**
     * Get exam details with questions and previous answers
     */
    public function getExamDetails(int $examId, int $studentId, int $perPage): array
    {
        return $this->examService->getDetails($examId, $studentId, $perPage);
    }

    /**
     * Start an exam attempt
     */
    public function startExam(int $examId, int $studentId): array
    {
        return $this->examService->startExam($examId, $studentId);
    }

    /**
     * Submit a single answer
     */
    public function submitAnswer(SubmitAnswerDTO $dto): array
    {
        return $this->examService->submitAnswer($dto);
    }

    /**
     * Send heartbeat to update remaining time
     */
    public function sendHeartbeat(SendHeartbeatDTO $dto): array
    {
        return $this->examService->sendHeartbeat($dto);
    }

    /**
     * Submit entire exam and get final results
     */
    public function submitEntireExam(SubmitEntireExamDTO $dto): array
    {
        return $this->examService->submitEntireExam($dto);
    }

    /**
     * Get exam/training statistics after submission
     */
    public function getStatistics(int $examTrainingId, int $studentId): array
    {
        return $this->examService->getStatistics($examTrainingId, $studentId);
    }

    /**
     * Get exam/training summary for a student
     */
    public function getSummary(int $examTrainingId, int $studentId): array
    {
        return $this->examService->getSummary($examTrainingId, $studentId);
    }
}
