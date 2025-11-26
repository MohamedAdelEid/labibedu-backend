<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\Models\StudentBook;
use App\Infrastructure\Models\Page;
use Carbon\Carbon;

class BookProgressSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📚 Starting Book Progress Seeding...');

        // Get completed book assignments
        $completedBookAssignments = DB::table('assignments')
            ->join('assignment_student', 'assignments.id', '=', 'assignment_student.assignment_id')
            ->join('books', 'assignments.assignable_id', '=', 'books.id')
            ->where('assignments.assignable_type', 'book')
            ->where('assignment_student.status', 'completed')
            ->select(
                'assignment_student.student_id',
                'books.id as book_id'
            )
            ->get();

        if ($completedBookAssignments->isEmpty()) {
            $this->command->warn('⚠️  No completed book assignments found!');
            return;
        }

        foreach ($completedBookAssignments as $assignment) {
            // Get the last page of the book (by id since there's no order column)
            $lastPage = Page::where('book_id', $assignment->book_id)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastPage) {
                StudentBook::create([
                    'student_id' => $assignment->student_id,
                    'book_id' => $assignment->book_id,
                    'last_read_page_id' => $lastPage->id,
                    'is_favorite' => false,
                ]);

                $this->command->info("   ✅ Created progress for Student {$assignment->student_id}, Book {$assignment->book_id}");
            } else {
                $this->command->warn("   ⚠️  No pages found for Book {$assignment->book_id}");
            }
        }

        $this->command->info('✅ Book Progress seeded successfully!');
        $this->command->info("📊 Total records created: " . $completedBookAssignments->count());
    }
}

