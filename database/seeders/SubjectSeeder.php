<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = ['Mathematics', 'Science', 'English', 'Arabic', 'History', 'Geography', 'Physics', 'Chemistry', 'Biology'];

        // Create subjects for each classroom
        for ($classroomId = 1; $classroomId <= 36; $classroomId++) {
            foreach ($subjects as $subject) {
                Subject::create([
                    'name' => $subject,
                    'classroom_id' => $classroomId,
                ]);
            }
        }
    }
}