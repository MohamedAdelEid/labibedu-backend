<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Create students (user_id 7-16 are students)
        for ($userId = 7; $userId <= 16; $userId++) {
            Student::create([
                'user_id' => $userId,
                'xp' => rand(0, 1000),
                'coins' => rand(0, 500),
                'date_of_birth' => now()->subYears(rand(10, 18)),
                'school_id' => rand(1, 3),
                'classroom_id' => rand(1, 36),
                'group_id' => rand(1, 108),
            ]);
        }
    }
}