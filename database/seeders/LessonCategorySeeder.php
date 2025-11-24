<?php

namespace Database\Seeders;

use App\Infrastructure\Models\LessonCategory;
use Illuminate\Database\Seeder;

class LessonCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
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
        ];

        foreach ($categories as $category) {
            LessonCategory::create($category);
        }

        $this->command->info('✅ Lesson categories seeded successfully!');
    }
}
