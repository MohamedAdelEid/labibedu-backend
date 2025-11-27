<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'title_ar' => 'إعادة التدوير',
                'title_en' => 'Recycling',
                'url' => 'https://youtu.be/stVbDrPusiw',
                'duration' => 180, // 3 minutes in seconds
                'xp' => 100,
                'coins' => 80,
                'marks' => 70,
                'subject_id' => null,
                'related_training_id' => null,
            ],
            [
                'title_ar' => 'درس الكيمياء',
                'title_en' => 'Chemistry Lesson',
                'url' => 'https://youtu.be/example123',
                'duration' => 240, // 4 minutes in seconds
                'xp' => 100,
                'coins' => 80,
                'marks' => 70,
                'subject_id' => null,
                'related_training_id' => null,
            ],
            [
                'title_ar' => 'درس الفيزياء',
                'title_en' => 'Physics Lesson',
                'url' => 'https://youtu.be/example456',
                'duration' => 300, // 5 minutes in seconds
                'xp' => 100,
                'coins' => 80,
                'marks' => 70,
                'subject_id' => null,
                'related_training_id' => null,
            ],
        ];

        foreach ($videos as $videoData) {
            // Generate folder name from Arabic title
            $folderName = $this->generateFolderName($videoData['title_ar']);

            // Create video folder structure
            $this->createVideoFolder($folderName);

            // Set cover path
            $videoData['cover'] = "videos/{$folderName}/cover.png";

            // Create video
            Video::create($videoData);

            $this->command->info("✅ Created video: {$videoData['title_ar']} in folder: {$folderName}");
        }

        $this->command->info('✅ Videos seeded successfully!');
    }

    /**
     * Generate folder name from Arabic title (same method as BookSeeder)
     */
    private function generateFolderName(string $title): string
    {
        if (class_exists('Transliterator')) {
            $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
            if ($transliterator) {
                $latinText = $transliterator->transliterate($title);
                return Str::slug($latinText);
            }
        }

        return Str::slug($title);
    }

    /**
     * Create folder structure for a video
     */
    private function createVideoFolder(string $folderName): void
    {
        $basePath = storage_path("app/public/videos/{$folderName}");

        // Create main video folder
        File::makeDirectory($basePath, 0755, true, true);

        // Create placeholder cover.png file (empty file, user can replace it later)
        $coverPath = "{$basePath}/cover.png";
        if (!File::exists($coverPath)) {
            File::put($coverPath, '');
        }
    }
}
