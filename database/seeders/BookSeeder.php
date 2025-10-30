<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Page;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'أساسيات الرياضيات',
                'cover' => 'books/math-basics/cover.jpg',
                'is_in_library' => true,
                'language' => 'ar',
                'has_sound' => true,
                'xp' => 120,
                'coins' => 50,
                'marks' => 85,
                'subject_id' => 1,
                'level_id' => 1,
                'related_training_id' => null,
            ],
            [
                'title' => 'علوم الطبيعة',
                'cover' => 'books/natural-sciences/cover.jpg',
                'is_in_library' => true,
                'language' => 'ar',
                'has_sound' => false,
                'xp' => 150,
                'coins' => 70,
                'marks' => 92,
                'subject_id' => 2,
                'level_id' => 2,
                'related_training_id' => null,
            ],
            [
                'title' => 'تعلم البرمجة',
                'cover' => 'books/programming/cover.jpg',
                'is_in_library' => true,
                'language' => 'ar',
                'has_sound' => true,
                'xp' => 200,
                'coins' => 100,
                'marks' => 95,
                'subject_id' => 3,
                'level_id' => 3,
                'related_training_id' => 1,
            ],
            [
                'title' => 'قصص الأطفال',
                'cover' => 'books/children-stories/cover.jpg',
                'is_in_library' => true,
                'language' => 'ar',
                'has_sound' => true,
                'xp' => 80,
                'coins' => 30,
                'marks' => 70,
                'subject_id' => 4,
                'level_id' => 1,
                'related_training_id' => null,
            ],
            [
                'title' => 'تاريخ العالم',
                'cover' => 'books/world-history/cover.jpg',
                'is_in_library' => false,
                'language' => 'ar',
                'has_sound' => false,
                'xp' => 180,
                'coins' => 90,
                'marks' => 88,
                'subject_id' => 5,
                'level_id' => 3,
                'related_training_id' => null,
            ],
            [
                'title' => 'اللغة العربية',
                'cover' => 'books/arabic-language/cover.jpg',
                'is_in_library' => true,
                'language' => 'ar',
                'has_sound' => true,
                'xp' => 140,
                'coins' => 60,
                'marks' => 80,
                'subject_id' => 6,
                'level_id' => 2,
                'related_training_id' => null,
            ],
        ];

        foreach ($books as $bookData) {
            $book = Book::create($bookData);

            // Create sample pages for each book
            for ($i = 1; $i <= 5; $i++) {
                Page::create([
                    'book_id' => $book->id,
                    'text' => "محتوى الصفحة {$i} من كتاب {$book->title}",
                    'image' => "books/{$book->id}/page-{$i}.jpg",
                    'mp3' => $book->has_sound ? "books/{$book->id}/page-{$i}.mp3" : null,
                    'is_text_to_speech' => !$book->has_sound,
                ]);
            }
        }
    }
}
