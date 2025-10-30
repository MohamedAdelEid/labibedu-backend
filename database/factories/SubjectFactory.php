<?php

namespace Database\Factories;

use App\Infrastructure\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Programming', 'Web Development', 'Design', 'Mathematics', 'Science']),
            'classroom_id' => null, // Can be set when creating
        ];
    }
}

