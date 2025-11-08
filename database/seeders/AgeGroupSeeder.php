<?php

namespace Database\Seeders;

use App\Infrastructure\Models\AgeGroup;
use Illuminate\Database\Seeder;

class AgeGroupSeeder extends Seeder
{
    public function run(): void
    {
        $ageGroups = [
            ['name' => '+3 years'],
            ['name' => '+6 years'],
            ['name' => '+9 years'],
            ['name' => '+12 years'],
        ];

        foreach ($ageGroups as $ageGroup) {
            AgeGroup::create($ageGroup);
        }
    }
}

