<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\Models\ExamAttempt;
use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerOrder;
use App\Infrastructure\Models\AnswerPair;
use App\Infrastructure\Models\AnswerGrade;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\ExamTraining;
use App\Domain\Enums\AttemptStatus;
use Carbon\Carbon;

class ExamAttemptAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎯 Starting Exam Attempt & Answers Seeding...');

        // Get completed exam/training IDs from assignments
        $completedExamTrainings = $this->getCompletedExamTrainings();

        if (empty($completedExamTrainings)) {
            $this->command->warn('⚠️  No completed exam/training assignments found!');
            return;
        }

        $totalAnswers = 0;

        foreach ($completedExamTrainings as $examTrainingData) {
            $examTraining = ExamTraining::find($examTrainingData['exam_training_id']);

            if (!$examTraining) {
                continue;
            }

            $this->command->info("📝 Creating attempt for: {$examTraining->title_ar}");

            // Create exam attempt
            $attempt = ExamAttempt::create([
                'student_id' => $examTrainingData['student_id'],
                'exam_training_id' => $examTraining->id,
                'start_time' => $examTrainingData['start_time'],
                'end_time' => $examTrainingData['end_time'],
                'remaining_seconds' => 0,
                'status' => AttemptStatus::FINISHED,
            ]);

            // Get all questions for this exam/training
            $questions = Question::where('exam_training_id', $examTraining->id)->with('options')->get();

            if ($questions->isEmpty()) {
                $this->command->warn("   ⚠️  No questions found!");
                continue;
            }

            $correctCount = 0;
            $wrongCount = 0;

            foreach ($questions as $index => $question) {
                // Answer 75% of questions correctly, 25% incorrectly
                $isCorrect = ($index % 4) < 3; // 3 out of 4 correct

                switch ($question->type->value) {
                    case 'choice':
                        $this->answerChoiceQuestion($question, $examTrainingData['student_id'], $isCorrect);
                        break;

                    case 'true_false':
                        $this->answerTrueFalseQuestion($question, $examTrainingData['student_id'], $isCorrect);
                        break;

                    case 'arrange':
                        $this->answerArrangeQuestion($question, $examTrainingData['student_id'], $isCorrect);
                        break;
                }

                if ($isCorrect) {
                    $correctCount++;
                } else {
                    $wrongCount++;
                }
                $totalAnswers++;
            }

            $this->command->info("   ✅ Created {$questions->count()} answers (✓ {$correctCount} correct, ✗ {$wrongCount} wrong)");
        }

        $this->command->info('✅ Exam Attempt & Answers seeded successfully!');
        $this->command->info("📊 Total answers created: {$totalAnswers}");
    }

    /**
     * Get completed exam/training assignments from database
     */
    private function getCompletedExamTrainings(): array
    {
        $completedAssignments = DB::table('assignments')
            ->join('assignment_student', 'assignments.id', '=', 'assignment_student.assignment_id')
            ->where('assignments.assignable_type', 'examTraining')
            ->where('assignment_student.status', 'completed')
            ->select(
                'assignments.assignable_id as exam_training_id',
                'assignment_student.student_id',
                'assignments.start_date',
                'assignments.end_date'
            )
            ->get()
            ->toArray();

        return array_map(function ($item) {
            return [
                'exam_training_id' => $item->exam_training_id,
                'student_id' => $item->student_id,
                'start_time' => Carbon::parse($item->start_date)->addHours(1),
                'end_time' => Carbon::parse($item->end_date)->subDays(1),
            ];
        }, $completedAssignments);
    }

    /**
     * Answer a choice question
     */
    private function answerChoiceQuestion(Question $question, int $studentId, bool $isCorrect): void
    {
        if ($isCorrect) {
            // Select the correct option
            $option = $question->options->where('is_correct', true)->first();
        } else {
            // Select a wrong option
            $option = $question->options->where('is_correct', false)->first();
        }

        if (!$option) {
            return;
        }

        Answer::create([
            'student_id' => $studentId,
            'question_id' => $question->id,
            'option_id' => $option->id,
            'submitted_at' => now(),
        ]);
    }

    /**
     * Answer a true/false question
     */
    private function answerTrueFalseQuestion(Question $question, int $studentId, bool $isCorrect): void
    {
        $option = $question->options->first();

        if (!$option) {
            return;
        }

        // If option->is_correct is true, then answer should be true for correct, false for wrong
        // If option->is_correct is false, then answer should be false for correct, true for wrong
        $answer = $isCorrect ? $option->is_correct : !$option->is_correct;

        Answer::create([
            'student_id' => $studentId,
            'question_id' => $question->id,
            'true_false_answer' => $answer,
            'submitted_at' => now(),
        ]);
    }

    /**
     * Answer an arrange question
     */
    private function answerArrangeQuestion(Question $question, int $studentId, bool $isCorrect): void
    {
        $options = $question->options;

        if ($options->isEmpty()) {
            return;
        }

        // Create the main answer
        $answer = Answer::create([
            'student_id' => $studentId,
            'question_id' => $question->id,
            'submitted_at' => now(),
        ]);

        if ($isCorrect) {
            // Arrange in correct order
            foreach ($options as $option) {
                AnswerOrder::create([
                    'answer_id' => $answer->id,
                    'option_id' => $option->id,
                    'order' => $option->arrange_order,
                ]);
            }
        } else {
            // Arrange in wrong order (reverse the order)
            $reversedOptions = $options->sortByDesc('arrange_order')->values();
            foreach ($reversedOptions as $index => $option) {
                AnswerOrder::create([
                    'answer_id' => $answer->id,
                    'option_id' => $option->id,
                    'order' => $index + 1,
                ]);
            }
        }
    }
}