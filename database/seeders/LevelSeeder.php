<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name_ar' => 'مبتدئ',
                'name_en' => 'Beginner',
            ],
            [
                'name_ar' => 'متوسط',
                'name_en' => 'Intermediate',
            ],
            [
                'name_ar' => 'متقدم',
                'name_en' => 'Advanced',
            ],
            [
                'name_ar' => 'متقن',
                'name_en' => 'Proficient',
            ],
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }
}

