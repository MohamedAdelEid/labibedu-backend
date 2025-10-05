<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 groups for each classroom
        for ($classroomId = 1; $classroomId <= 36; $classroomId++) {
            for ($i = 1; $i <= 3; $i++) {
                Group::create([
                    'classroom_id' => $classroomId,
                    'name' => "Group $i",
                ]);
            }
        }
    }
}