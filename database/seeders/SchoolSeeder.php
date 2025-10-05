<?php

namespace Database\Seeders;

use App\Infrastructure\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::create(['name' => 'Cairo International School', 'address' => 'New Cairo, Cairo, Egypt']);
        School::create(['name' => 'Alexandria Modern School', 'address' => 'Smouha, Alexandria, Egypt']);
        School::create(['name' => 'Giza Elite Academy', 'address' => 'Dokki, Giza, Egypt']);
    }
}