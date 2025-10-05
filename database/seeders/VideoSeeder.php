<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            ['title' => 'Introduction to Algebra', 'title_ar' => 'مقدمة في الجبر', 'subject_id' => 1],
            ['title' => 'The Scientific Method', 'title_ar' => 'المنهج العلمي', 'subject_id' => 2],
            ['title' => 'English Tenses Explained', 'title_ar' => 'شرح الأزمنة الإنجليزية', 'subject_id' => 3],
            ['title' => 'Arabic Poetry Analysis', 'title_ar' => 'تحليل الشعر العربي', 'subject_id' => 4],
            ['title' => 'Ancient Civilizations', 'title_ar' => 'الحضارات القديمة', 'subject_id' => 5],
        ];

        foreach ($videos as $video) {
            Video::create([
                'title' => $video['title'],
                'title_ar' => $video['title_ar'],
                'subject_id' => $video['subject_id'],
                'url' => 'https://youtube.com/watch?v=' . bin2hex(random_bytes(5)),
            ]);
        }
    }
}