<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = ['Mathematics', 'Science', 'English', 'Arabic', 'History'];

        // Create teachers (user_id 2-6 are teachers)
        for ($userId = 2; $userId <= 6; $userId++) {
            Teacher::create([
                'user_id' => $userId,
                'specialization' => $specializations[$userId - 2],
                'hire_date' => now()->subYears(rand(1, 10)),
                'school_id' => rand(1, 3),
            ]);
        }
    }
}