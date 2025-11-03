<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Convert Arabic title to English slug for folder naming
     */
    private function titleToSlug(string $title): string
    {
        // Use PHP Intl extension for proper transliteration
        if (class_exists('Transliterator')) {
            $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
            if ($transliterator) {
                $latinText = $transliterator->transliterate($title);
                return Str::slug($latinText);
            }
        }

        // Fallback to Str::slug if Transliterator is not available
        return Str::slug($title);
    }

    /**
     * Create folder structure for a book
     */
    private function createBookFolders(string $folderName, int $numberOfPages = 3): void
    {
        $basePath = storage_path("app/public/books/{$folderName}");

        // Create main book folder
        File::makeDirectory($basePath, 0755, true, true);

        // Create pages folder and subfolders for each page
        for ($i = 1; $i <= $numberOfPages; $i++) {
            File::makeDirectory("{$basePath}/pages/page_{$i}", 0755, true, true);
        }
    }

    public function run(): void
    {
        $books = [
            [
                'title' => 'سناء في الفضاء',
                'is_in_library' => true,
                'language' => 'ar',
                'has_sound' => true,
                'xp' => 100,
                'coins' => 40,
                'marks' => 75,
                'subject_id' => 4,
                'level_id' => 1,
                'related_training_id' => 10,
                'number_of_pages' => 3,
            ],
        ];

        foreach ($books as $bookData) {
            // Extract number_of_pages before creating book
            $numberOfPages = $bookData['number_of_pages'] ?? 5;
            unset($bookData['number_of_pages']);

            // Generate folder name from title
            $folderName = $this->titleToSlug($bookData['title']);

            // Add cover and thumbnail paths (stored as relative paths)
            $bookData['cover'] = "books/{$folderName}/cover.svg";
            $bookData['thumbnail'] = "books/{$folderName}/thumbnail.jpg";

            $book = Book::create($bookData);

            // Create folder structure
            $this->createBookFolders($folderName, $numberOfPages);

            // Create sample pages for each book
            for ($i = 1; $i <= $numberOfPages; $i++) {
                Page::create([
                    'book_id' => $book->id,
                    'text' => "محتوى الصفحة {$i} من كتاب {$book->title}",
                    'image' => "books/{$folderName}/pages/page_{$i}/image.png",
                    'mp3' => $book->has_sound ? "books/{$folderName}/pages/page_{$i}/audio.mp3" : null,
                    'is_text_to_speech' => !$book->has_sound,
                ]);
            }
        }
    }
}
