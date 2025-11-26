<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\ExamTraining;
use Carbon\Carbon;

class ExamTrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎯 Starting Book Trainings Seeding...');

        $trainingsData = $this->getBookTrainingsData();

        foreach ($trainingsData as $trainingData) {
            ExamTraining::create($trainingData);
            $this->command->info("✅ Created training: {$trainingData['title_ar']}");
        }

        $this->command->info('✅ Book trainings seeded successfully!');
        $this->command->info('📊 Total trainings created: ' . count($trainingsData));
    }

    /**
     * Get all book trainings and exams data configuration
     * 
     * To add a new book training, simply add a new array to this method
     */
    private function getBookTrainingsData(): array
    {
        return [
            // ========== EXAMS ==========
            // Exam 1: Mathematics
            [
                'title' => 'Mathematics Final Exam',
                'title_ar' => 'اختبار الرياضيات النهائي',
                'description' => 'Final exam covering all mathematics topics',
                'description_ar' => 'اختبار نهائي يغطي جميع موضوعات الرياضيات',
                'type' => 'exam',
                'duration' => 60, // 60 minutes
                'created_by' => 1,
                'subject_id' => 1,
                'group_id' => null,
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(7),
            ],

            // Exam 2: Arabic Language
            [
                'title' => 'Arabic Language Exam',
                'title_ar' => 'اختبار اللغة العربية',
                'description' => 'Comprehensive Arabic language exam',
                'description_ar' => 'اختبار شامل في اللغة العربية',
                'type' => 'exam',
                'duration' => 45,
                'created_by' => 1,
                'subject_id' => 2,
                'group_id' => null,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->subDays(1),
            ],

            // ========== TRAININGS ==========
            // Training for Book: سناء في الفضاء
            [
                'title' => 'Training: Sanaa in Space',
                'title_ar' => 'تدريب كتاب سناء في الفضاء',
                'description' => 'Training exercises for the book Sanaa in Space',
                'description_ar' => 'تمارين تدريبية لكتاب سناء في الفضاء',
                'type' => 'training',
                'duration' => null,
                'created_by' => 1,
                'subject_id' => null,
                'group_id' => null,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => null,
            ],

            // Training for Book: آدم يتخيل النحلة
            [
                'title' => 'Training: Adam Imagines the Bee',
                'title_ar' => 'تدريب كتاب آدم يتخيل النحلة',
                'description' => 'Training exercises for the book Adam Imagines the Bee',
                'description_ar' => 'تمارين تدريبية لكتاب آدم يتخيل النحلة',
                'type' => 'training',
                'duration' => null,
                'created_by' => 1,
                'subject_id' => null,
                'group_id' => null,
                'start_date' => Carbon::now()->subDays(8),
                'end_date' => null,
            ],

            // Training for Book: عندما فقدت قطتي عقلها
            [
                'title' => 'Training: When My Cat Lost Her Mind',
                'title_ar' => 'تدريب كتاب عندما فقدت قطتي عقلها',
                'description' => 'Training exercises for the book When My Cat Lost Her Mind',
                'description_ar' => 'تمارين تدريبية لكتاب عندما فقدت قطتي عقلها',
                'type' => 'training',
                'duration' => null,
                'created_by' => 1,
                'subject_id' => null,
                'group_id' => null,
                'start_date' => Carbon::now()->subDays(6),
                'end_date' => null,
            ],

            // Training for Book: لماذا انا مربع
            [
                'title' => 'Training: Why Am I Square',
                'title_ar' => 'تدريب كتاب لماذا انا مربع',
                'description' => 'Training exercises for the book Why Am I Square',
                'description_ar' => 'تمارين تدريبية لكتاب لماذا انا مربع',
                'type' => 'training',
                'duration' => null,
                'created_by' => 1,
                'subject_id' => null,
                'group_id' => null,
                'start_date' => Carbon::now()->subDays(4),
                'end_date' => null,
            ],
        ];
    }
}