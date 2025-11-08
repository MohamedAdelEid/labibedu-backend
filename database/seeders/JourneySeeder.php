<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JourneySeeder extends Seeder
{
    /**
     * Run the Journey module seeders in order
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Journey Module Seeding...');
        $this->command->newLine();

        // 1. Seed Levels
        $this->command->info('📊 Seeding Journey Levels...');
        $this->call(JourneyLevelSeeder::class);
        $this->command->newLine();

        // 2. Seed Stages
        $this->command->info('🎯 Seeding Journey Stages...');
        $this->call(JourneyStageSeeder::class);
        $this->command->newLine();

        // 3. Seed Stage Contents
        $this->command->info('📚 Seeding Stage Contents...');
        $this->call(StageContentSeeder::class);
        $this->command->newLine();

        // 4. Seed Sample Student Progress (optional)
        $this->command->info('👤 Seeding Sample Student Progress...');
        $this->call(StudentStageProgressSeeder::class);
        $this->command->newLine();

        $this->command->info('✅ Journey Module Seeding Completed Successfully!');
    }
}

