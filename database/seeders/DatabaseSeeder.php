<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // SchoolSeeder::class,
            // GradeSeeder::class,
            // ClassroomSeeder::class,
            // GroupSeeder::class,
            // UserSeeder::class,
            // StudentSeeder::class,
            // TeacherSeeder::class,
            // SubjectSeeder::class,
            // TeacherSubjectGroupSeeder::class,
            // VideoSeeder::class,
            // LevelSeeder::class,
            // ExamTrainingSeeder::class,
            // BookSeeder::class,
            // QuestionSeeder::class,
            // AssignmentSeeder::class,
            // ExamAttemptAnswerSeeder::class,
            // AvatarCategorySeeder::class,
            // AvatarSeeder::class,
            LibrarySeeder::class,
        ]);
    }
}
