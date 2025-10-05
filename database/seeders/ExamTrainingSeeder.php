<?php

namespace Database\Seeders;

use App\Infrastructure\Models\ExamTraining;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExamTrainingSeeder extends Seeder
{
    public function run(): void
    {
        // Exam 1: Mathematics Midterm
        ExamTraining::create([
            'title' => 'Mathematics Midterm Exam',
            'title_ar' => 'امتحان منتصف الفصل في الرياضيات',
            'description' => 'Comprehensive midterm exam covering algebra and geometry',
            'description_ar' => 'امتحان شامل لمنتصف الفصل يغطي الجبر والهندسة',
            'type' => 'exam',
            'duration' => 90,
            'created_by' => 1,
            'subject_id' => 1,
            'video_id' => 1,
            'book_id' => 1,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5),
        ]);

        // Training 1: Algebra Practice
        ExamTraining::create([
            'title' => 'Algebra Practice Training',
            'title_ar' => 'تدريب على الجبر',
            'description' => 'Practice questions for algebra concepts',
            'description_ar' => 'أسئلة تدريبية على مفاهيم الجبر',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => 1,
            'video_id' => 1,
            'book_id' => 1,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => null,
        ]);

        // Exam 2: Science Final
        ExamTraining::create([
            'title' => 'Science Final Exam',
            'title_ar' => 'الامتحان النهائي للعلوم',
            'description' => 'Final exam covering all science topics',
            'description_ar' => 'امتحان نهائي يغطي جميع موضوعات العلوم',
            'type' => 'exam',
            'duration' => 120,
            'created_by' => 2,
            'subject_id' => 2,
            'video_id' => 2,
            'book_id' => 2,
            'group_id' => 1,
            'start_date' => Carbon::now()->addDays(1),
            'end_date' => Carbon::now()->addDays(7),
        ]);

        // Training 2: English Grammar
        ExamTraining::create([
            'title' => 'English Grammar Training',
            'title_ar' => 'تدريب على قواعد اللغة الإنجليزية',
            'description' => 'Practice exercises for English grammar',
            'description_ar' => 'تمارين تدريبية على قواعد اللغة الإنجليزية',
            'type' => 'training',
            'duration' => null,
            'created_by' => 3,
            'subject_id' => 3,
            'video_id' => 3,
            'book_id' => 3,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => null,
        ]);
    }
}