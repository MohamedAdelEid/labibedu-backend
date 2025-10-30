<?php

namespace Database\Factories;

use App\Infrastructure\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Level>
 */
class LevelFactory extends Factory
{
    protected $model = Level::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ar' => fake()->randomElement(['مبتدئ', 'متوسط', 'متقدم', 'متقن']),
            'name_en' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced', 'Proficient']),
        ];
    }
}

