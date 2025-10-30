<?php

namespace Database\Factories;

use App\Infrastructure\Models\Student;
use App\Infrastructure\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'xp' => fake()->numberBetween(0, 5000),
            'coins' => fake()->numberBetween(0, 1000),
            'date_of_birth' => fake()->dateTimeBetween('-18 years', '-10 years'),
            'school_id' => fake()->optional()->numberBetween(1, 3),
            'classroom_id' => fake()->optional()->numberBetween(1, 36),
            'group_id' => fake()->optional()->numberBetween(1, 108),
            'active_avatar_id' => null,
        ];
    }
}

