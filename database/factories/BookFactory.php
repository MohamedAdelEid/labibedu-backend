<?php

namespace Database\Factories;

use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Level;
use App\Infrastructure\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'cover' => 'books/' . fake()->slug() . '/cover.jpg',
            'is_in_library' => true,
            'language' => 'ar',
            'has_sound' => fake()->boolean(60), // 60% chance of having sound
            'xp' => fake()->numberBetween(80, 250),
            'coins' => fake()->numberBetween(30, 150),
            'marks' => fake()->numberBetween(70, 100),
            'subject_id' => Subject::inRandomOrder()->first()?->id ?? Subject::factory(),
            'level_id' => Level::inRandomOrder()->first()?->id ?? Level::factory(),
            'related_training_id' => null,
        ];
    }
}

