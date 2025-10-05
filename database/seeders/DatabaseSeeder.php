<?php

namespace Database\Seeders;

use App\Infrastructure\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            SchoolSeeder::class,
            GradeSeeder::class,
            ClassroomSeeder::class,
            GroupSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            SubjectSeeder::class,
            TeacherSubjectGroupSeeder::class,
            BookSeeder::class,
            VideoSeeder::class,
            ExamTrainingSeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
