<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Teacher;
use App\Infrastructure\Models\Subject;
use App\Infrastructure\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSubjectGroupSeeder extends Seeder
{
    public function run(): void
    {
        // Get all teachers
        $teachers = Teacher::all();

        // Get all subjects
        $subjects = Subject::all();

        // Get all groups
        $groups = Group::all();

        // Create teacher-subject-group relationships
        $teacherSubjectGroupData = [];

        foreach ($teachers as $teacher) {
            // Each teacher teaches 2-4 subjects
            $teacherSubjects = $subjects->random(rand(2, 4));

            foreach ($teacherSubjects as $subject) {
                // For each subject, assign the teacher to 1-3 groups in the same classroom
                $subjectGroups = $groups->where('classroom_id', $subject->classroom_id)->random(rand(1, 3));

                foreach ($subjectGroups as $group) {
                    $teacherSubjectGroupData[] = [
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'group_id' => $group->id,
                    ];
                }
            }
        }

        // Remove duplicates (in case the same combination exists)
        $teacherSubjectGroupData = array_unique($teacherSubjectGroupData, SORT_REGULAR);

        // Insert the data
        foreach ($teacherSubjectGroupData as $data) {
            DB::table('teacher_subject_group')->insertOrIgnore($data);
        }

        // Alternative approach: Create specific assignments for better control
        $this->createSpecificAssignments();
    }

    private function createSpecificAssignments(): void
    {
        // Get specific teachers by specialization
        $mathTeacher = Teacher::where('specialization', 'Mathematics')->first();
        $scienceTeacher = Teacher::where('specialization', 'Science')->first();
        $englishTeacher = Teacher::where('specialization', 'English')->first();
        $arabicTeacher = Teacher::where('specialization', 'Arabic')->first();
        $historyTeacher = Teacher::where('specialization', 'History')->first();

        if (!$mathTeacher || !$scienceTeacher || !$englishTeacher || !$arabicTeacher || !$historyTeacher) {
            return;
        }

        // Assign teachers to subjects and groups based on their specialization
        $assignments = [
            // Mathematics teacher assignments
            [
                'teacher_id' => $mathTeacher->id,
                'subject_name' => 'Mathematics',
                'classroom_ids' => [1, 2, 3, 4, 5, 6], // First 6 classrooms
                'groups_per_classroom' => 2,
            ],
            // Science teacher assignments
            [
                'teacher_id' => $scienceTeacher->id,
                'subject_name' => 'Science',
                'classroom_ids' => [1, 2, 3, 4, 5, 6],
                'groups_per_classroom' => 2,
            ],
            // English teacher assignments
            [
                'teacher_id' => $englishTeacher->id,
                'subject_name' => 'English',
                'classroom_ids' => [1, 2, 3, 4, 5, 6],
                'groups_per_classroom' => 2,
            ],
            // Arabic teacher assignments
            [
                'teacher_id' => $arabicTeacher->id,
                'subject_name' => 'Arabic',
                'classroom_ids' => [1, 2, 3, 4, 5, 6],
                'groups_per_classroom' => 2,
            ],
            // History teacher assignments
            [
                'teacher_id' => $historyTeacher->id,
                'subject_name' => 'History',
                'classroom_ids' => [1, 2, 3, 4, 5, 6],
                'groups_per_classroom' => 2,
            ],
        ];

        foreach ($assignments as $assignment) {
            $subject = Subject::where('name', $assignment['subject_name'])->first();

            if (!$subject) {
                continue;
            }

            foreach ($assignment['classroom_ids'] as $classroomId) {
                // Get groups for this classroom
                $classroomGroups = Group::where('classroom_id', $classroomId)
                    ->inRandomOrder()
                    ->limit($assignment['groups_per_classroom'])
                    ->get();

                foreach ($classroomGroups as $group) {
                    DB::table('teacher_subject_group')->insertOrIgnore([
                        'teacher_id' => $assignment['teacher_id'],
                        'subject_id' => $subject->id,
                        'group_id' => $group->id,
                    ]);
                }
            }
        }
    }
}
