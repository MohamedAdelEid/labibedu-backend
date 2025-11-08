<?php

namespace App\Infrastructure\Facades;

use App\Application\Services\JourneyService;
use Illuminate\Support\Collection;

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
     * Get student's journey levels with calculated progress
     * Returns Eloquent models for Resources to format
     */
    public function getStudentJourney(int $studentId): Collection
    {
        return $this->journeyService->getLevelsForStudent($studentId);
    }
}
