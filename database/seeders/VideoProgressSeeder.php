<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\Models\VideoProgress;
use Carbon\Carbon;

class VideoProgressSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎥 Starting Video Progress Seeding...');

        // Get completed video assignments
        $completedVideoAssignments = DB::table('assignments')
            ->join('assignment_student', 'assignments.id', '=', 'assignment_student.assignment_id')
            ->join('videos', 'assignments.assignable_id', '=', 'videos.id')
            ->where('assignments.assignable_type', 'video')
            ->where('assignment_student.status', 'completed')
            ->select(
                'assignment_student.student_id',
                'videos.id as video_id',
                'videos.duration'
            )
            ->get();

        if ($completedVideoAssignments->isEmpty()) {
            $this->command->warn('⚠️  No completed video assignments found!');
            return;
        }

        foreach ($completedVideoAssignments as $assignment) {
            VideoProgress::create([
                'student_id' => $assignment->student_id,
                'video_id' => $assignment->video_id,
                'watched_duration' => $assignment->duration ?? 120, // Full duration or default 120 seconds
                'is_completed' => true,
            ]);

            $this->command->info("   ✅ Created progress for Student {$assignment->student_id}, Video {$assignment->video_id}");
        }

        $this->command->info('✅ Video Progress seeded successfully!');
        $this->command->info("📊 Total records created: " . $completedVideoAssignments->count());
    }
}

