<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // Assignment 1: Mathematics Exam
        $assignment1 = Assignment::create([
            'title' => 'Mathematics Midterm Exam',
            'type' => 'exam',
            'exam_training_id' => 1,
            'teacher_id' => 1,
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(5),
        ]);

        // Assign to students 1-4
        for ($studentId = 1; $studentId <= 4; $studentId++) {
            DB::table('assignment_student')->insert([
                'assignment_id' => $assignment1->id,
                'student_id' => $studentId,
                'status' => 'not_submitted',
                'assigned_at' => now()->subDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assignment 2: Algebra Training
        $assignment2 = Assignment::create([
            'title' => 'Algebra Practice Training',
            'type' => 'training',
            'exam_training_id' => 2,
            'teacher_id' => 1,
            'start_date' => now()->subDays(10),
            'end_date' => null,
        ]);

        // Assign to students 1-4
        for ($studentId = 1; $studentId <= 4; $studentId++) {
            DB::table('assignment_student')->insert([
                'assignment_id' => $assignment2->id,
                'student_id' => $studentId,
                'status' => 'not_submitted',
                'assigned_at' => now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assignment 3: Video - Introduction to Algebra
        $assignment3 = Assignment::create([
            'title' => 'Introduction to Algebra Video',
            'type' => 'video',
            'video_id' => 1,
            'teacher_id' => 1,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(20),
        ]);

        // Assign to students 1-4
        for ($studentId = 1; $studentId <= 4; $studentId++) {
            DB::table('assignment_student')->insert([
                'assignment_id' => $assignment3->id,
                'student_id' => $studentId,
                'status' => 'not_submitted',
                'assigned_at' => now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assignment 4: Book - Mathematics Grade 10
        $assignment4 = Assignment::create([
            'title' => 'Mathematics Grade 10 Book',
            'type' => 'book',
            'book_id' => 1,
            'teacher_id' => 1,
            'start_date' => now()->subDays(15),
            'end_date' => now()->addDays(30),
        ]);

        // Assign to students 1-4
        for ($studentId = 1; $studentId <= 4; $studentId++) {
            DB::table('assignment_student')->insert([
                'assignment_id' => $assignment4->id,
                'student_id' => $studentId,
                'status' => 'not_submitted',
                'assigned_at' => now()->subDays(15),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
