<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        Video::create([
            'title' => 'Introduction to Algebra',
            'url' => 'https://youtube.com/watch?v=algebra101',
            'duration' => 1800, // 30 minutes
            'xp' => 50,
            'coins' => 25,
            'marks' => 10,
            'subject_id' => 1,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(20),
        ]);

        Video::create([
            'title' => 'The Scientific Method',
            'url' => 'https://youtube.com/watch?v=science101',
            'duration' => 2400, // 40 minutes
            'xp' => 60,
            'coins' => 30,
            'marks' => 12,
            'subject_id' => 2,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(15),
        ]);
    }
}
