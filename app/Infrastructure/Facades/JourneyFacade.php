<?php

namespace App\Infrastructure\Facades;

use App\Application\Services\JourneyService;

/**
 * JourneyFacade - Provides a simplified interface between Controller and Journey Services
 * 
 * This facade acts as the entry point for all journey-related operations,
 * coordinating between the journey service while keeping the controller thin.
 */
class JourneyFacade
{
    public function __construct(
        private JourneyService $journeyService
    ) {
    }

    /**
     * Get student's journey with levels, stages, and progress
     */
    public function getStudentJourney(int $studentId): array
    {
        return $this->journeyService->getStudentJourney($studentId);
    }
}

