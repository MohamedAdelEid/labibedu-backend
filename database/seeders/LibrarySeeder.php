<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Level;
use App\Infrastructure\Models\Page;
use App\Infrastructure\Models\Student;
use App\Infrastructure\Models\StudentBook;
use App\Infrastructure\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get existing Levels (from LevelSeeder)
        $levels = $this->getLevels();

        // 2. Create library-specific subjects (not tied to classrooms)
        $subjects = $this->createLibrarySubjects();

        // 3. Create library books with proper subject/level mapping
        $books = $this->createLibraryBooks($levels, $subjects);

        // 4. Create pages for each book (using bulk insert)
        $this->createBookPages($books);

        // 5. Link student (id: 1) with books
        $this->linkStudentWithBooks($books);
    }

    /**
     * Get existing levels from database (created by LevelSeeder)
     */
    private function getLevels(): Collection
    {
        $levels = Level::all();

        if ($levels->isEmpty()) {
            throw new \Exception('No levels found. Please run LevelSeeder first.');
        }

        return $levels;
    }

    /**
     * Create library-specific subjects (not tied to classrooms)
     */
    private function createLibrarySubjects(): Collection
    {
        $subjectsData = [
            ['name' => 'Programming', 'classroom_id' => null],
            ['name' => 'Web Development', 'classroom_id' => null],
            ['name' => 'Design', 'classroom_id' => null],
        ];

        $subjects = collect();

        foreach ($subjectsData as $subjectData) {
            $subject = Subject::firstOrCreate(
                ['name' => $subjectData['name'], 'classroom_id' => null],
                $subjectData
            );
            $subjects->push($subject);
        }

        return $subjects;
    }

    /**
     * Create library books with logical subject/level distribution
     */
    private function createLibraryBooks(Collection $levels, Collection $subjects): Collection
    {
        $booksData = [
            [
                'title' => 'مقدمة في البرمجة',
                'cover' => 'books/intro-programming/cover.jpg',
                'subject' => 'Programming',
                'level' => 'Beginner',
                'has_sound' => true,
                'xp' => 120,
                'coins' => 50,
                'marks' => 85,
            ],
            [
                'title' => 'تطوير الويب الأساسي',
                'cover' => 'books/web-development-basics/cover.jpg',
                'subject' => 'Web Development',
                'level' => 'Beginner',
                'has_sound' => false,
                'xp' => 150,
                'coins' => 70,
                'marks' => 90,
            ],
            [
                'title' => 'مبادئ التصميم',
                'cover' => 'books/design-principles/cover.jpg',
                'subject' => 'Design',
                'level' => 'Beginner',
                'has_sound' => true,
                'xp' => 180,
                'coins' => 80,
                'marks' => 95,
            ],
            [
                'title' => 'برمجة متقدمة',
                'cover' => 'books/advanced-programming/cover.jpg',
                'subject' => 'Programming',
                'level' => 'Advanced',
                'has_sound' => true,
                'xp' => 250,
                'coins' => 120,
                'marks' => 100,
            ],
            [
                'title' => 'إطار عمل Laravel',
                'cover' => 'books/laravel-framework/cover.jpg',
                'subject' => 'Web Development',
                'level' => 'Intermediate',
                'has_sound' => false,
                'xp' => 200,
                'coins' => 100,
                'marks' => 92,
            ],
            [
                'title' => 'تصميم واجهات المستخدم',
                'cover' => 'books/ui-design/cover.jpg',
                'subject' => 'Design',
                'level' => 'Intermediate',
                'has_sound' => true,
                'xp' => 160,
                'coins' => 75,
                'marks' => 88,
            ],
            [
                'title' => 'قواعد البيانات',
                'cover' => 'books/databases/cover.jpg',
                'subject' => 'Programming',
                'level' => 'Intermediate',
                'has_sound' => true,
                'xp' => 140,
                'coins' => 65,
                'marks' => 85,
            ],
            [
                'title' => 'جافاسكريبت الحديث',
                'cover' => 'books/modern-javascript/cover.jpg',
                'subject' => 'Web Development',
                'level' => 'Advanced',
                'has_sound' => false,
                'xp' => 220,
                'coins' => 110,
                'marks' => 96,
            ],
            [
                'title' => 'تطبيقات React',
                'cover' => 'books/react-applications/cover.jpg',
                'subject' => 'Web Development',
                'level' => 'Proficient',
                'has_sound' => true,
                'xp' => 190,
                'coins' => 95,
                'marks' => 94,
            ],
            [
                'title' => 'التصميم التفاعلي',
                'cover' => 'books/interactive-design/cover.jpg',
                'subject' => 'Design',
                'level' => 'Advanced',
                'has_sound' => false,
                'xp' => 170,
                'coins' => 85,
                'marks' => 90,
            ],
        ];

        $books = collect();

        foreach ($booksData as $bookData) {
            // Find subject by name
            $subject = $subjects->firstWhere('name', $bookData['subject']);
            if (!$subject) {
                throw new \Exception("Subject '{$bookData['subject']}' not found.");
            }

            // Find level by English name
            $level = $levels->firstWhere('name_en', $bookData['level']);
            if (!$level) {
                throw new \Exception("Level '{$bookData['level']}' not found.");
            }

            $book = Book::firstOrCreate(
                ['title' => $bookData['title']],
                [
                    'title' => $bookData['title'],
                    'cover' => $bookData['cover'],
                    'is_in_library' => true,
                    'language' => 'ar',
                    'has_sound' => $bookData['has_sound'],
                    'xp' => $bookData['xp'],
                    'coins' => $bookData['coins'],
                    'marks' => $bookData['marks'],
                    'subject_id' => $subject->id,
                    'level_id' => $level->id,
                    'related_training_id' => null,
                ]
            );

            $books->push($book);
        }

        return $books;
    }

    /**
     * Create pages for each book using bulk insert for better performance
     */
    private function createBookPages(Collection $books): void
    {
        $pagesToInsert = [];
        $now = now();

        foreach ($books as $book) {
            // Skip if book already has pages
            if ($book->pages()->exists()) {
                continue;
            }

            $pageCount = rand(5, 10);

            for ($i = 1; $i <= $pageCount; $i++) {
                $pagesToInsert[] = [
                    'book_id' => $book->id,
                    'text' => "هذا هو محتوى الصفحة {$i} من كتاب {$book->title}. يحتوي على معلومات قيمة ومفيدة للقارئ.",
                    'image' => "books/{$book->id}/page-{$i}.jpg",
                    'mp3' => $book->has_sound ? "books/{$book->id}/page-{$i}.mp3" : null,
                    'is_text_to_speech' => !$book->has_sound,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Bulk insert pages for better performance
        if (!empty($pagesToInsert)) {
            Page::insert($pagesToInsert);
        }
    }

    /**
     * Link student (id: 1) with library books
     */
    private function linkStudentWithBooks(Collection $books): void
    {
        $student = Student::find(1);

        if (!$student) {
            throw new \Exception('Student with id 1 not found. Please ensure StudentSeeder has run first.');
        }

        // Link student with 60-80% of books
        $booksToLinkCount = (int) ceil($books->count() * (rand(60, 80) / 100));
        $booksToLink = $books->random(min($booksToLinkCount, $books->count()));

        $studentBooksToInsert = [];
        $now = now();

        foreach ($booksToLink as $book) {
            // Skip if relationship already exists
            if (
                StudentBook::where('student_id', $student->id)
                    ->where('book_id', $book->id)
                    ->exists()
            ) {
                continue;
            }

            $isFavorite = rand(0, 1) === 1;
            $hasStartedReading = rand(0, 1) === 1;
            $lastReadPageId = null;

            if ($hasStartedReading && $book->pages()->exists()) {
                $randomPage = $book->pages()->inRandomOrder()->first();
                $lastReadPageId = $randomPage->id;
            }

            $studentBooksToInsert[] = [
                'student_id' => $student->id,
                'book_id' => $book->id,
                'is_favorite' => $isFavorite,
                'last_read_page_id' => $lastReadPageId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Bulk insert student-book relationships
        if (!empty($studentBooksToInsert)) {
            StudentBook::insert($studentBooksToInsert);
        }
    }
}

