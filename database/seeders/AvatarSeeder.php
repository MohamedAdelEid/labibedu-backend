<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Avatar;
use App\Infrastructure\Models\AvatarCategory;
use App\Infrastructure\Models\Student;
use App\Infrastructure\Models\User;
use Illuminate\Database\Seeder;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $labibCategory = AvatarCategory::where('name_en', 'labib')->first();
        $dinosaurCategory = AvatarCategory::where('name_en', 'dinosaur')->first();
        $robotCategory = AvatarCategory::where('name_en', 'robot')->first();
        $astroCategory = AvatarCategory::where('name_en', 'astro')->first();

        // Labib Avatars (5 avatars)
        $labibAvatars = [
            [
                'url' => 'labib/labib_1.svg',
                'coins' => 10,
                'category_id' => $labibCategory->id,
            ],
            [
                'url' => 'labib/labib_2.svg',
                'coins' => 15,
                'category_id' => $labibCategory->id,
            ],
            [
                'url' => 'labib/labib_3.svg',
                'coins' => 20,
                'category_id' => $labibCategory->id,
            ],
            [
                'url' => 'labib/labib_4.svg',
                'coins' => 25,
                'category_id' => $labibCategory->id,
            ],
            [
                'url' => 'labib/labib_5.svg',
                'coins' => 30,
                'category_id' => $labibCategory->id,
            ],
        ];

        // Dinosaur Avatars (5 avatars)
        $dinosaurAvatars = [
            [
                'url' => 'dinosaur/dinosaur_1.svg',
                'coins' => 12,
                'category_id' => $dinosaurCategory->id,
            ],
            [
                'url' => 'dinosaur/dinosaur_2.svg',
                'coins' => 18,
                'category_id' => $dinosaurCategory->id,
            ],
            [
                'url' => 'dinosaur/dinosaur_3.svg',
                'coins' => 22,
                'category_id' => $dinosaurCategory->id,
            ],
            [
                'url' => 'dinosaur/dinosaur_4.svg',
                'coins' => 28,
                'category_id' => $dinosaurCategory->id,
            ],
            [
                'url' => 'dinosaur/dinosaur_5.svg',
                'coins' => 35,
                'category_id' => $dinosaurCategory->id,
            ],
        ];

        // Robot Avatars (5 avatars)
        $robotAvatars = [
            [
                'url' => 'robot/robot_1.svg',
                'coins' => 14,
                'category_id' => $robotCategory->id,
            ],
            [
                'url' => 'robot/robot_2.svg',
                'coins' => 16,
                'category_id' => $robotCategory->id,
            ],
            [
                'url' => 'robot/robot_3.svg',
                'coins' => 24,
                'category_id' => $robotCategory->id,
            ],
            [
                'url' => 'robot/robot_4.svg',
                'coins' => 32,
                'category_id' => $robotCategory->id,
            ],
            [
                'url' => 'robot/robot_5.svg',
                'coins' => 40,
                'category_id' => $robotCategory->id,
            ],
        ];

        $astroAvatars = [
            [
                'url' => 'astro/astro_1.svg',
                'coins' => 10,
                'category_id' => $astroCategory->id,
            ],
            [
                'url' => 'astro/astro_2.svg',
                'coins' => 15,
                'category_id' => $astroCategory->id,
            ],
            [
                'url' => 'astro/astro_3.svg',
                'coins' => 20,
                'category_id' => $astroCategory->id,
            ],
            [
                'url' => 'astro/astro_4.svg',
                'coins' => 25,
                'category_id' => $astroCategory->id,
            ],
            [
                'url' => 'astro/astro_5.svg',
                'coins' => 30,
                'category_id' => $astroCategory->id,
            ],
        ];

        // Create all avatars
        $allAvatars = array_merge($labibAvatars, $dinosaurAvatars, $robotAvatars);

        foreach ($allAvatars as $avatarData) {
            Avatar::create($avatarData);
        }

        // Get all students
        $students = Student::all();

        // Get avatars once (they're the same for all students)
        $firstLabibAvatar = Avatar::where('category_id', $labibCategory->id)->first();
        $firstTwoDinosaurAvatars = Avatar::where('category_id', $dinosaurCategory->id)->limit(2)->get();
        $firstRobotAvatar = Avatar::where('category_id', $robotCategory->id)->first();

        // Assign avatars to all students
        foreach ($students as $student) {
            // Purchase first avatar from Labib category
            $student->avatars()->attach($firstLabibAvatar->id, ['purchased_at' => now()]);
            // $student->coins -= $firstLabibAvatar->coins;

            // Purchase first two avatars from Dinosaur category
            foreach ($firstTwoDinosaurAvatars as $avatar) {
                $student->avatars()->attach($avatar->id, ['purchased_at' => now()]);
                // $student->coins -= $avatar->coins;
            }

            // Purchase first avatar from Robot category
            $student->avatars()->attach($firstRobotAvatar->id, ['purchased_at' => now()]);
            // $student->coins -= $firstRobotAvatar->coins;

            // Set first Labib avatar as active
            $student->active_avatar_id = $firstLabibAvatar->id;

            // Reset coins to 15 for all students
            $student->coins = 15;

            $student->save();
        }

        $this->command->info('✅ Assigned avatars and reset coins to 15 for all students');
    }
}