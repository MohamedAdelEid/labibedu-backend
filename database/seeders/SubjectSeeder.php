<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['ar' => 'الرياضيات', 'en' => 'Mathematics'],
            ['ar' => 'العلوم', 'en' => 'Science'],
            ['ar' => 'اللغة الإنجليزية', 'en' => 'English'],
            ['ar' => 'اللغة العربية', 'en' => 'Arabic'],
            ['ar' => 'التاريخ', 'en' => 'History'],
            ['ar' => 'الجغرافيا', 'en' => 'Geography'],
            ['ar' => 'الفيزياء', 'en' => 'Physics'],
            ['ar' => 'الكيمياء', 'en' => 'Chemistry'],
            ['ar' => 'الأحياء', 'en' => 'Biology'],
        ];

        // Create subjects for each classroom
        for ($classroomId = 1; $classroomId <= 36; $classroomId++) {
            foreach ($subjects as $subject) {
                Subject::create([
                    'name_ar' => $subject['ar'],
                    'name_en' => $subject['en'],
                    'classroom_id' => $classroomId,
                ]);
            }
        }
    }
}