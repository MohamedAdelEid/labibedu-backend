<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Student;
use App\Infrastructure\Models\JourneyStage;
use App\Infrastructure\Models\StudentStageProgress;
use App\Domain\Enums\StudentStageStatus;
use Illuminate\Database\Seeder;

class StudentStageProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates sample progress for testing purposes
     */
    public function run(): void
    {
        // Get first student for testing
        $student = Student::first();

        if (!$student) {
            $this->command->warn('No students found. Please seed students first.');
            return;
        }

        // Get all stages from first level
        $firstLevelStages = JourneyStage::whereHas('level', function ($query) {
            $query->where('order', 1);
        })->orderBy('order')->get();

        if ($firstLevelStages->isEmpty()) {
            $this->command->warn('No stages found. Please run JourneyStageSeeder first.');
            return;
        }

        // Create sample progress:
        // - First stage: completed with 3 stars
        // - Second stage: in progress with 2 stars
        // - Third stage: completed with 2 stars
        // - Rest: not started

        foreach ($firstLevelStages as $index => $stage) {
            $status = StudentStageStatus::NOT_STARTED;
            $stars = 0;

            if ($index === 0) {
                // First stage completed
                $status = StudentStageStatus::COMPLETED;
                $stars = 3;
            } elseif ($index === 1) {
                // Second stage in progress
                $status = StudentStageStatus::IN_PROGRESS;
                $stars = 2;
            } elseif ($index === 2) {
                // Third stage completed
                $status = StudentStageStatus::COMPLETED;
                $stars = 2;
            }

            StudentStageProgress::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'stage_id' => $stage->id,
                ],
                [
                    'earned_stars' => $stars,
                    'status' => $status->value,
                ]
            );
        }

        $this->command->info('Student Stage Progress seeded successfully!');
        $this->command->info("Created progress records for student: {$student->id}");
    }
}

