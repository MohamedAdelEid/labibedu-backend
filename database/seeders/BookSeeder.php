<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['title' => 'Mathematics Grade 10', 'title_ar' => 'الرياضيات الصف العاشر', 'subject_id' => 1],
            ['title' => 'Science Fundamentals', 'title_ar' => 'أساسيات العلوم', 'subject_id' => 2],
            ['title' => 'English Grammar', 'title_ar' => 'قواعد اللغة الإنجليزية', 'subject_id' => 3],
            ['title' => 'Arabic Literature', 'title_ar' => 'الأدب العربي', 'subject_id' => 4],
            ['title' => 'World History', 'title_ar' => 'التاريخ العالمي', 'subject_id' => 5],
        ];

        foreach ($books as $book) {
            Book::create([
                'title' => $book['title'],
                'title_ar' => $book['title_ar'],
                'subject_id' => $book['subject_id'],
                'url' => 'https://example.com/books/' . str_replace(' ', '-', strtolower($book['title'])) . '.pdf',
            ]);
        }
    }
}