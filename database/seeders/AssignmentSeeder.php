<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Assignment;
use App\Infrastructure\Models\ExamTraining;
use App\Infrastructure\Models\Video;
use App\Infrastructure\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get first available records
        $examTraining = ExamTraining::first();
        $video = Video::first();
        $book = Book::first();

        if (!$examTraining || !$video || !$book) {
            $this->command->warn('Please seed ExamTrainings, Videos, and Books first!');
            return;
        }

        // Assignment 1: ExamTraining (not_started)
        $assignment1 = Assignment::create([
            'title_ar' => 'اختبار الرياضيات النهائي',
            'title_en' => 'Mathematics Final Exam',
            'assignable_type' => 'examTraining',
            'assignable_id' => $examTraining->id,
            'teacher_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(7),
        ]);

        // Assign to students with different statuses
        DB::table('assignment_student')->insert([
            [
                'assignment_id' => $assignment1->id,
                'student_id' => 1,
                'status' => 'not_started',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assignment_id' => $assignment1->id,
                'student_id' => 2,
                'status' => 'in_progress',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Assignment 2: ExamTraining (completed)
        $assignment2 = Assignment::create([
            'title_ar' => 'تدريب على الجبر',
            'title_en' => 'Algebra Training',
            'assignable_type' => 'examTraining',
            'assignable_id' => $examTraining->id,
            'teacher_id' => 1,
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(3),
        ]);

        DB::table('assignment_student')->insert([
            'assignment_id' => $assignment2->id,
            'student_id' => 1,
            'status' => 'completed',
            'assigned_at' => now()->subDays(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assignment 3: Video (not_started)
        $assignment3 = Assignment::create([
            'title_ar' => 'مشاهدة درس الفيزياء',
            'title_en' => 'Physics Lesson Video',
            'assignable_type' => 'video',
            'assignable_id' => $video->id,
            'teacher_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(14),
        ]);

        DB::table('assignment_student')->insert([
            [
                'assignment_id' => $assignment3->id,
                'student_id' => 1,
                'status' => 'not_started',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assignment_id' => $assignment3->id,
                'student_id' => 2,
                'status' => 'in_progress',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Assignment 4: Book (not_started)
        $assignment4 = Assignment::create([
            'title_ar' => 'قراءة كتاب الأدب العربي',
            'title_en' => 'Arabic Literature Book Reading',
            'assignable_type' => 'book',
            'assignable_id' => $book->id,
            'teacher_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        DB::table('assignment_student')->insert([
            [
                'assignment_id' => $assignment4->id,
                'student_id' => 1,
                'status' => 'not_started',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assignment_id' => $assignment4->id,
                'student_id' => 2,
                'status' => 'completed',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✅ Assignments seeded successfully!');
        $this->command->info('   - 2 ExamTraining assignments');
        $this->command->info('   - 1 Video assignment');
        $this->command->info('   - 1 Book assignment');
        $this->command->info('   - Various statuses: not_started, in_progress, completed');
    }
}
