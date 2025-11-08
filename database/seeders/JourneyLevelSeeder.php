<?php

namespace Database\Seeders;

use App\Infrastructure\Models\JourneyLevel;
use Illuminate\Database\Seeder;

class JourneyLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name_ar' => 'المستوى الأول - البداية',
                'name_en' => 'Level 1 - Beginning',
                'order' => 1,
            ],
            [
                'name_ar' => 'المستوى الثاني - المتوسط',
                'name_en' => 'Level 2 - Intermediate',
                'order' => 2,
            ],
            [
                'name_ar' => 'المستوى الثالث - المتقدم',
                'name_en' => 'Level 3 - Advanced',
                'order' => 3,
            ],
            [
                'name_ar' => 'المستوى الرابع - الخبير',
                'name_en' => 'Level 4 - Expert',
                'order' => 4,
            ],
        ];

        foreach ($levels as $level) {
            JourneyLevel::firstOrCreate(
                ['order' => $level['order']],
                $level
            );
        }

        $this->command->info('Journey Levels seeded successfully!');
    }
}

