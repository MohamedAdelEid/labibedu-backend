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
        $this->command->info('🎯 Starting Assignments Seeding...');

        // Get all exam trainings
        $trainings = ExamTraining::where('type', 'training')->get();
        $videos = Video::all();
        $books = Book::all();

        if ($trainings->isEmpty() || $videos->isEmpty() || $books->isEmpty()) {
            $this->command->warn('⚠️  Please seed ExamTrainings, Videos, and Books first!');
            return;
        }

        // ========== CURRENT TAB (not_started) ==========
        $this->command->info('📝 Creating CURRENT (not_started) assignments...');

        // Current: Training (not_started)
        $this->createAssignment([
            'title_ar' => 'تدريب جديد - سناء في الفضاء',
            'title_en' => 'New Training - Sanaa in Space',
            'assignable_type' => 'examTraining',
            'assignable_id' => $trainings->first()->id,
            'teacher_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(14),
        ], 1, 'not_started');

        // Current: Video (not_started)
        $this->createAssignment([
            'title_ar' => 'مشاهدة درس جديد',
            'title_en' => 'Watch New Lesson',
            'assignable_type' => 'video',
            'assignable_id' => $videos->first()->id,
            'teacher_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(10),
        ], 1, 'not_started');

        // Current: Book (not_started)
        $this->createAssignment([
            'title_ar' => 'قراءة كتاب جديد',
            'title_en' => 'Read New Book',
            'assignable_type' => 'book',
            'assignable_id' => $books->first()->id,
            'teacher_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ], 1, 'not_started');

        // ========== TRAINING TAB (completed) ==========
        $this->command->info('📝 Creating TRAINING (completed) assignments...');

        $this->createAssignment([
            'title_ar' => 'تدريب كتاب سناء في الفضاء',
            'title_en' => 'Training: Sanaa in Space',
            'assignable_type' => 'examTraining',
            'assignable_id' => $trainings->first()->id,
            'teacher_id' => 1,
            'start_date' => now()->subDays(15),
            'end_date' => now()->subDays(5),
        ], 1, 'completed');

        $this->createAssignment([
            'title_ar' => 'تدريب كتاب آدم يتخيل النحلة',
            'title_en' => 'Training: Adam Imagines the Bee',
            'assignable_type' => 'examTraining',
            'assignable_id' => $trainings->skip(1)->first()->id ?? $trainings->first()->id,
            'teacher_id' => 1,
            'start_date' => now()->subDays(12),
            'end_date' => now()->subDays(3),
        ], 1, 'in_progress');

        // ========== WATCHING TAB (completed) ==========
        $this->command->info('📝 Creating WATCHING (completed) assignments...');

        $this->createAssignment([
            'title_ar' => 'مشاهدة درس الفيزياء',
            'title_en' => 'Physics Lesson Video',
            'assignable_type' => 'video',
            'assignable_id' => $videos->first()->id,
            'teacher_id' => 1,
            'start_date' => now()->subDays(8),
            'end_date' => now()->subDays(1),
        ], 1, 'completed');

        $this->createAssignment([
            'title_ar' => 'مشاهدة درس الكيمياء',
            'title_en' => 'Chemistry Lesson Video',
            'assignable_type' => 'video',
            'assignable_id' => $videos->skip(1)->first()->id ?? $videos->first()->id,
            'teacher_id' => 1,
            'start_date' => now()->subDays(6),
            'end_date' => now()->addDays(3),
        ], 1, 'in_progress');

        // ========== READING TAB (completed) ==========
        $this->command->info('📝 Creating READING (completed) assignments...');

        $this->createAssignment([
            'title_ar' => 'قراءة كتاب الأدب العربي',
            'title_en' => 'Arabic Literature Book',
            'assignable_type' => 'book',
            'assignable_id' => $books->first()->id,
            'teacher_id' => 1,
            'start_date' => now()->subDays(20),
            'end_date' => now()->subDays(5),
        ], 1, 'completed');

        $this->createAssignment([
            'title_ar' => 'قراءة كتاب التاريخ',
            'title_en' => 'History Book',
            'assignable_type' => 'book',
            'assignable_id' => $books->skip(1)->first()->id ?? $books->first()->id,
            'teacher_id' => 1,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(5),
        ], 1, 'in_progress');

        $this->command->info('✅ Assignments seeded successfully!');
        $this->command->info('   📊 Summary:');
        $this->command->info('   - Current (not_started): 3 assignments');
        $this->command->info('   - Training: 2 assignments');
        $this->command->info('   - Watching: 2 assignments');
        $this->command->info('   - Reading: 2 assignments');
    }

    private function createAssignment(array $data, int $studentId, string $status): void
    {
        $assignment = Assignment::create($data);

        DB::table('assignment_student')->insert([
            'assignment_id' => $assignment->id,
            'student_id' => $studentId,
            'status' => $status,
            'assigned_at' => $data['start_date'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
