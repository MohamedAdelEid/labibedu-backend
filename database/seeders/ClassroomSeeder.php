<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        // Create classrooms for each school and grade
        for ($schoolId = 1; $schoolId <= 3; $schoolId++) {
            for ($gradeId = 1; $gradeId <= 12; $gradeId++) {
                Classroom::create([
                    'school_id' => $schoolId,
                    'grade_id' => $gradeId,
                ]);
            }
        }
    }
}