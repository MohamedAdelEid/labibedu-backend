<?php

namespace Database\Seeders;

use App\Infrastructure\Models\JourneyStage;
use App\Infrastructure\Models\StageContent;
use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Video;
use App\Infrastructure\Models\ExamTraining;
use Illuminate\Database\Seeder;

class StageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all stages
        $stages = JourneyStage::with('level')->orderBy('level_id')->orderBy('order')->get();

        if ($stages->isEmpty()) {
            $this->command->warn('No journey stages found. Please run JourneyStageSeeder first.');
            return;
        }

        // Get available content
        $books = Book::all();
        $videos = Video::all();
        $examTrainings = ExamTraining::all();

        if ($books->isEmpty() || $videos->isEmpty() || $examTrainings->isEmpty()) {
            $this->command->warn('Warning: Some content types are missing. Please seed Books, Videos, and ExamTrainings first.');
        }

        $bookIndex = 0;
        $videoIndex = 0;
        $examIndex = 0;

        foreach ($stages as $stage) {
            // Determine how many content items per stage (1-3 items)
            $contentCount = rand(1, 3);

            for ($i = 0; $i < $contentCount; $i++) {
                $contentId = null;
                $contentType = $stage->type;

                // Get content based on type
                switch ($contentType) {
                    case 'book':
                        if ($books->isNotEmpty()) {
                            $contentId = $books[$bookIndex % $books->count()]->id;
                            $bookIndex++;
                        }
                        break;

                    case 'video':
                        if ($videos->isNotEmpty()) {
                            $contentId = $videos[$videoIndex % $videos->count()]->id;
                            $videoIndex++;
                        }
                        break;

                    case 'examTraining':
                        if ($examTrainings->isNotEmpty()) {
                            $contentId = $examTrainings[$examIndex % $examTrainings->count()]->id;
                            $examIndex++;
                        }
                        break;
                }

                if ($contentId) {
                    StageContent::firstOrCreate(
                        [
                            'stage_id' => $stage->id,
                            'content_type' => $contentType,
                            'content_id' => $contentId,
                        ]
                    );
                }
            }
        }

        $this->command->info('Stage Contents seeded successfully!');
        $this->command->info("Total stages: {$stages->count()}");
        $this->command->info("Total contents created: " . StageContent::count());
    }
}

