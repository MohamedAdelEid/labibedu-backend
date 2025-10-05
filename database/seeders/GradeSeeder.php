<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        // Primary grades
        for ($i = 1; $i <= 6; $i++) {
            Grade::create(['name' => "Grade $i", 'level' => 'primary']);
        }

        // Preparatory grades
        for ($i = 7; $i <= 9; $i++) {
            Grade::create(['name' => "Grade $i", 'level' => 'preparatory']);
        }

        // Secondary grades
        for ($i = 10; $i <= 12; $i++) {
            Grade::create(['name' => "Grade $i", 'level' => 'secondary']);
        }
    }
}