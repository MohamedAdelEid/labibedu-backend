<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SchoolSeeder::class,
            GradeSeeder::class,
            ClassroomSeeder::class,
            GroupSeeder::class,
            AgeGroupSeeder::class,
            LevelSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            SubjectSeeder::class,
            TeacherSubjectGroupSeeder::class,
            VideoSeeder::class,
            ExamTrainingSeeder::class,
            BookSeeder::class,
            LessonCategorySeeder::class,
            LessonSeeder::class,
            QuestionSeeder::class,
                // Assignment must be seeded before progress seeders
            AssignmentSeeder::class,
            ExamAttemptAnswerSeeder::class,
            VideoProgressSeeder::class,
            BookProgressSeeder::class,
            AvatarCategorySeeder::class,
            AvatarSeeder::class,
                // LibrarySeeder::class,
            JourneySeeder::class,
        ]);
    }
}
