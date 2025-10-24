<?php

namespace Database\Seeders;

use App\Infrastructure\Models\AvatarCategory;
use Illuminate\Database\Seeder;

class AvatarCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'labib',
                'name_ar' => 'لبيب',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name_en' => 'dinosaur',
                'name_ar' => 'ديناصور',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name_en' => 'robot',
                'name_ar' => 'روبوت',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name_en' => 'astro',
                'name_ar' => 'أسترو',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            AvatarCategory::create($category);
        }
    }
}
