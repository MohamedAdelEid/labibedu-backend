<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\ExamTraining;
use Carbon\Carbon;

class ExamTrainingSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // EXAMS
        // ============================================

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
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5),
        ]);

        // Exam 2: Science Final
        ExamTraining::create([
            'title' => 'Science Final Exam',
            'title_ar' => 'الامتحان النهائي للعلوم',
            'description' => 'Final exam covering physics, chemistry, and biology',
            'description_ar' => 'امتحان نهائي يغطي الفيزياء والكيمياء والأحياء',
            'type' => 'exam',
            'duration' => 120,
            'created_by' => 2,
            'subject_id' => 2,
            'group_id' => 1,
            'start_date' => Carbon::now()->addDays(1),
            'end_date' => Carbon::now()->addDays(7),
        ]);

        // Exam 3: English Grammar Test
        ExamTraining::create([
            'title' => 'English Grammar Test',
            'title_ar' => 'اختبار قواعد اللغة الإنجليزية',
            'description' => 'Test covering tenses, articles, and sentence structure',
            'description_ar' => 'اختبار يغطي الأزمنة والأدوات وتركيب الجمل',
            'type' => 'exam',
            'duration' => 60,
            'created_by' => 3,
            'subject_id' => 3,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(3),
        ]);

        // ============================================
        // TRAININGS
        // ============================================

        // Training 1: Algebra Practice
        ExamTraining::create([
            'title' => 'Algebra Practice Training',
            'title_ar' => 'تدريب على الجبر',
            'description' => 'Practice questions for algebra concepts and equations',
            'description_ar' => 'أسئلة تدريبية على مفاهيم الجبر والمعادلات',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => 1,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => null,
        ]);

        // Training 2: Physics Fundamentals
        ExamTraining::create([
            'title' => 'Physics Fundamentals Training',
            'title_ar' => 'تدريب على أساسيات الفيزياء',
            'description' => 'Practice exercises for basic physics concepts',
            'description_ar' => 'تمارين تدريبية على المفاهيم الأساسية للفيزياء',
            'type' => 'training',
            'duration' => null,
            'created_by' => 2,
            'subject_id' => 2,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => null,
        ]);

        // Training 3: English Vocabulary Builder
        ExamTraining::create([
            'title' => 'English Vocabulary Builder',
            'title_ar' => 'بناء المفردات الإنجليزية',
            'description' => 'Expand your English vocabulary with practical exercises',
            'description_ar' => 'وسع مفرداتك الإنجليزية مع تمارين عملية',
            'type' => 'training',
            'duration' => null,
            'created_by' => 3,
            'subject_id' => 3,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(7),
            'end_date' => null,
        ]);

        // ============================================
        // ARABIC EXAMS
        // ============================================

        // Exam 4: الرياضيات - الفصل الدراسي الأول
        ExamTraining::create([
            'title' => 'Mathematics First Semester',
            'title_ar' => 'الرياضيات - الفصل الدراسي الأول',
            'description' => 'Comprehensive mathematics exam for first semester',
            'description_ar' => 'امتحان شامل في الرياضيات للفصل الدراسي الأول',
            'type' => 'exam',
            'duration' => 90,
            'created_by' => 1,
            'subject_id' => 1,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(5),
        ]);

        // Exam 5: العلوم - الفصل الدراسي الأول
        ExamTraining::create([
            'title' => 'Science First Semester',
            'title_ar' => 'العلوم - الفصل الدراسي الأول',
            'description' => 'Comprehensive science exam for first semester',
            'description_ar' => 'امتحان شامل في العلوم للفصل الدراسي الأول',
            'type' => 'exam',
            'duration' => 120,
            'created_by' => 2,
            'subject_id' => 2,
            'group_id' => 1,
            'start_date' => Carbon::now()->addDays(1),
            'end_date' => Carbon::now()->addDays(7),
        ]);

        // Exam 6: اللغة العربية - النحو والإملاء
        ExamTraining::create([
            'title' => 'Arabic Grammar and Spelling',
            'title_ar' => 'اللغة العربية - النحو والإملاء',
            'description' => 'Arabic grammar and spelling comprehensive test',
            'description_ar' => 'اختبار شامل في النحو والإملاء للغة العربية',
            'type' => 'exam',
            'duration' => 75,
            'created_by' => 3,
            'subject_id' => 4,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(4),
        ]);

        // ============================================
        // ARABIC TRAININGS
        // ============================================

        // Training 4: تدريب الجبر
        ExamTraining::create([
            'title' => 'Algebra Training',
            'title_ar' => 'تدريب الجبر',
            'description' => 'Algebra practice exercises and problems',
            'description_ar' => 'تمارين تدريبية في الجبر وحل المسائل',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => 1,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(8),
            'end_date' => null,
        ]);

        // Training 5: تدريب الفيزياء الأساسية
        ExamTraining::create([
            'title' => 'Basic Physics Training',
            'title_ar' => 'تدريب الفيزياء الأساسية',
            'description' => 'Basic physics concepts and exercises',
            'description_ar' => 'مفاهيم الفيزياء الأساسية والتمارين',
            'type' => 'training',
            'duration' => null,
            'created_by' => 2,
            'subject_id' => 2,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(6),
            'end_date' => null,
        ]);

        // Training 6: تدريب مفردات اللغة العربية
        ExamTraining::create([
            'title' => 'Arabic Vocabulary Training',
            'title_ar' => 'تدريب مفردات اللغة العربية',
            'description' => 'Arabic vocabulary building and language exercises',
            'description_ar' => 'بناء المفردات العربية وتمارين اللغة',
            'type' => 'training',
            'duration' => null,
            'created_by' => 3,
            'subject_id' => 4,
            'group_id' => 1,
            'start_date' => Carbon::now()->subDays(9),
            'end_date' => null,
        ]);

        $this->command->info('✅ Exam trainings seeded successfully!');
        $this->command->info('📊 Created: 6 exams and 6 trainings (3 English + 3 Arabic)');
    }
}
