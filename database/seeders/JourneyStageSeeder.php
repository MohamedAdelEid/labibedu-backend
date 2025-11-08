<?php

namespace Database\Seeders;

use App\Infrastructure\Models\JourneyLevel;
use App\Infrastructure\Models\JourneyStage;
use Illuminate\Database\Seeder;

class JourneyStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all levels
        $levels = JourneyLevel::orderBy('order')->get();

        if ($levels->isEmpty()) {
            $this->command->warn('No journey levels found. Please run JourneyLevelSeeder first.');
            return;
        }

        // Define stages for each level
        $stagesTemplate = [
            // Level 1 stages
            1 => [
                ['type' => 'book', 'order' => 1],
                ['type' => 'video', 'order' => 2],
                ['type' => 'examTraining', 'order' => 3],
                ['type' => 'book', 'order' => 4],
                ['type' => 'examTraining', 'order' => 5],
            ],
            // Level 2 stages
            2 => [
                ['type' => 'video', 'order' => 1],
                ['type' => 'book', 'order' => 2],
                ['type' => 'examTraining', 'order' => 3],
                ['type' => 'book', 'order' => 4],
                ['type' => 'video', 'order' => 5],
                ['type' => 'examTraining', 'order' => 6],
            ],
            // Level 3 stages
            3 => [
                ['type' => 'book', 'order' => 1],
                ['type' => 'examTraining', 'order' => 2],
                ['type' => 'video', 'order' => 3],
                ['type' => 'book', 'order' => 4],
                ['type' => 'examTraining', 'order' => 5],
            ],
            // Level 4 stages
            4 => [
                ['type' => 'video', 'order' => 1],
                ['type' => 'book', 'order' => 2],
                ['type' => 'video', 'order' => 3],
                ['type' => 'examTraining', 'order' => 4],
                ['type' => 'book', 'order' => 5],
                ['type' => 'examTraining', 'order' => 6],
            ],
        ];

        foreach ($levels as $level) {
            $stages = $stagesTemplate[$level->order] ?? [];

            foreach ($stages as $stageData) {
                JourneyStage::firstOrCreate(
                    [
                        'level_id' => $level->id,
                        'order' => $stageData['order'],
                    ],
                    [
                        'type' => $stageData['type'],
                    ]
                );
            }
        }

        $this->command->info('Journey Stages seeded successfully!');
    }
}

