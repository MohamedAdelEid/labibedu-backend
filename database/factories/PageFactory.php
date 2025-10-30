<?php

namespace Database\Factories;

use App\Infrastructure\Models\Page;
use App\Infrastructure\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'text' => fake()->paragraph(3),
            'image' => 'books/' . fake()->uuid() . '/page-' . fake()->numberBetween(1, 10) . '.jpg',
            'mp3' => fake()->optional(0.7)->passthrough('books/' . fake()->uuid() . '/page-' . fake()->numberBetween(1, 10) . '.mp3'),
            'is_text_to_speech' => fake()->boolean(40), // 40% chance of TTS
        ];
    }
}

