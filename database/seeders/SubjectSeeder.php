<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['ar' => 'اللغة العربية', 'en' => 'Arabic'],
            ['ar' => 'الرياضيات', 'en' => 'Mathematics'],
            ['ar' => 'العلوم', 'en' => 'Science'],
            ['ar' => 'اللغة الإنجليزية', 'en' => 'English'],
        ];

        // Create subjects only for Grade 5 (classroom_id = 1)
        foreach ($subjects as $subject) {
            Subject::create([
                'name_ar' => $subject['ar'],
                'name_en' => $subject['en'],
                'classroom_id' => 3, // Grade 5
            ]);
        }
    }
}