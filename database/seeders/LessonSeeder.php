<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Lesson;
use App\Infrastructure\Models\Grade;
use App\Infrastructure\Models\Subject;
use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Video;
use App\Infrastructure\Models\LessonCategory;
use App\Infrastructure\Models\ExamTraining;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // Get first grade (should be KG One)
        $grade1 = Grade::first();
        // Get second grade (should be KG Two)
        $grade2 = Grade::skip(1)->first();

        if (!$grade1 || !$grade2) {
            $this->command->warn('No grades found. Please run GradeSeeder first.');
            return;
        }

        // Get some subjects for testing (from first classroom)
        $subjects = Subject::where('classroom_id', 1)->take(3)->get();

        if ($subjects->isEmpty()) {
            $this->command->warn('No subjects found. Please run SubjectSeeder first.');
            return;
        }

        $subject1 = $subjects->first();
        $subject2 = $subjects->skip(1)->first() ?? $subjects->first();
        $subject3 = $subjects->skip(2)->first() ?? $subjects->first();

        // Get categories
        $beginnerCategory = LessonCategory::where('name_en', 'Beginner')->first();
        $intermediateCategory = LessonCategory::where('name_en', 'Intermediate')->first();
        $advancedCategory = LessonCategory::where('name_en', 'Advanced')->first();

        if (!$beginnerCategory || !$intermediateCategory || !$advancedCategory) {
            $this->command->warn('No categories found. Please run LessonCategorySeeder first.');
            return;
        }

        // Get a training for testing
        $training = ExamTraining::first();

        // Get books for testing
        $books = Book::take(3)->get();
        // Get videos for testing
        $videos = Video::take(2)->get();

        // Create lessons
        $lessons = [
            // Lesson 1: Beginner level
            [
                'title_ar' => 'مقدمة في الأرقام',
                'title_en' => 'Introduction to Numbers',
                'category_id' => $beginnerCategory->id,
                'grade_id' => $grade1->id,
                'subject_id' => $subject1->id,
                'train_id' => $training?->id,
                'books' => $books->take(1)->pluck('id')->toArray(),
                'videos' => $videos->take(1)->pluck('id')->toArray(),
            ],
            // Lesson 2: Intermediate level
            [
                'title_ar' => 'الجمع والطرح',
                'title_en' => 'Addition and Subtraction',
                'category_id' => $intermediateCategory->id,
                'grade_id' => $grade1->id,
                'subject_id' => $subject1->id,
                'train_id' => $training?->id,
                'books' => $books->take(2)->pluck('id')->toArray(),
                'videos' => $videos->take(1)->pluck('id')->toArray(),
            ],
            // Lesson 3: Advanced level
            [
                'title_ar' => 'الضرب والقسمة',
                'title_en' => 'Multiplication and Division',
                'category_id' => $advancedCategory->id,
                'grade_id' => $grade1->id,
                'subject_id' => $subject1->id,
                'train_id' => $training?->id,
                'books' => $books->take(2)->pluck('id')->toArray(),
                'videos' => $videos->pluck('id')->toArray(),
            ],
            // Lesson 4: Different subject
            [
                'title_ar' => 'أساسيات القراءة',
                'title_en' => 'Reading Basics',
                'category_id' => $beginnerCategory->id,
                'grade_id' => $grade1->id,
                'subject_id' => $subject2->id,
                'train_id' => $training?->id,
                'books' => $books->take(1)->pluck('id')->toArray(),
                'videos' => $videos->take(1)->pluck('id')->toArray(),
            ],
            // Lesson 5: Different grade
            [
                'title_ar' => 'الجمل البسيطة',
                'title_en' => 'Simple Sentences',
                'category_id' => $intermediateCategory->id,
                'grade_id' => $grade2->id,
                'subject_id' => $subject2->id,
                'train_id' => $training?->id,
                'books' => $books->skip(1)->take(1)->pluck('id')->toArray(),
                'videos' => $videos->pluck('id')->toArray(),
            ],
        ];

        foreach ($lessons as $lessonData) {
            // Extract books and videos
            $booksIds = $lessonData['books'] ?? [];
            $videosIds = $lessonData['videos'] ?? [];
            unset($lessonData['books'], $lessonData['videos']);

            // Create lesson
            $lesson = Lesson::create($lessonData);

            // Attach books
            if (!empty($booksIds)) {
                $lesson->books()->attach($booksIds);
            }

            // Attach videos
            if (!empty($videosIds)) {
                $lesson->videos()->attach($videosIds);
            }
        }

        $this->command->info('Created ' . count($lessons) . ' lessons with relationships.');
    }
}

