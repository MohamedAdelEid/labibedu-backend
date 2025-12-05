<?php

namespace App\Application\UseCases\Journey;

use App\Application\DTOs\Journey\GetJourneyDTO;
use App\Application\Services\JourneyDataService;
use App\Domain\Interfaces\Repositories\StudentRepositoryInterface;
use Illuminate\Support\Facades\Log;

class GetJourneyUseCase
{
    public function __construct(
        private JourneyDataService $journeyDataService,
        private StudentRepositoryInterface $studentRepository
    ) {
    }

    public function execute(GetJourneyDTO $dto): array
    {
        $this->validateStudent($dto->studentId);

        $journeyData = $this->journeyDataService->getJourneyData($dto->studentId);

        $this->logJourneyAccess($dto->studentId, $journeyData);

        return $journeyData;
    }

    private function validateStudent(int $studentId): void
    {
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new \RuntimeException('Student not found');
        }
    }

    private function logJourneyAccess(int $studentId, array $journeyData): void
    {
        Log::info('Journey data accessed', [
            'student_id' => $studentId,
            'levels_count' => count($journeyData['levels'] ?? []),
            'has_upcoming_exam' => $journeyData['exam']['hasUpcoming'] ?? false,
        ]);
    }
}

