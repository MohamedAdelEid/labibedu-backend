<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'Mathematics Grade 10',
            'url' => 'https://example.com/books/mathematics-grade-10.pdf',
            'pages_count' => 250,
            'xp' => 100,
            'coins' => 50,
            'marks' => 20,
            'subject_id' => 1,
            'start_date' => now()->subDays(15),
            'end_date' => now()->addDays(30),
        ]);

        Book::create([
            'title' => 'Science Fundamentals',
            'url' => 'https://example.com/books/science-fundamentals.pdf',
            'pages_count' => 300,
            'xp' => 120,
            'coins' => 60,
            'marks' => 25,
            'subject_id' => 2,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(25),
        ]);
    }
}
