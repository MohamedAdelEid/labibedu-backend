<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerSelection;
use App\Infrastructure\Models\ExamTraining;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnswerRepository extends BaseRepository implements AnswerRepositoryInterface
{
    public function __construct(Answer $model)
    {
        parent::__construct($model);
    }

    public function createWithSelections(array $answerData, array $selections): array
    {
        return DB::transaction(function () use ($answerData, $selections) {
            $question = Question::with('options')->findOrFail($answerData['question_id']);
            
            // Calculate correctness and rewards based on question type
            $result = $this->evaluateAnswer($question, $selections, $answerData['user_answer'] ?? null);

            // Create answer record
            $answer = $this->create([
                'student_id' => $answerData['student_id'],
                'question_id' => $answerData['question_id'],
                'user_answer' => $answerData['user_answer'] ?? null,
                'gained_xp' => $result['gained_xp'],
                'gained_coins' => $result['gained_coins'],
                'submitted_at' => now(),
            ]);

            // Create answer selections (not for written questions)
            if ($question->type->value !== 'written') {
                foreach ($result['selections'] as $selection) {
                    AnswerSelection::create([
                        'answer_id' => $answer->id,
                        'option_id' => $selection['option_id'],
                        'is_correct' => $selection['is_correct'],
                        'gained_xp' => $selection['gained_xp'],
                        'gained_coins' => $selection['gained_coins'],
                        'order' => $selection['order'] ?? null,
                    ]);
                }
            }

            // Update student xp and coins
            $student = Student::findOrFail($answerData['student_id']);
            $student->xp += $result['gained_xp'];
            $student->coins += $result['gained_coins'];
            $student->save();

            return [
                'answer' => $answer,
                'is_correct' => $result['is_correct'],
                'gained_xp' => $result['gained_xp'],
                'gained_coins' => $result['gained_coins'],
            ];
        });
    }

    // <CHANGE> New method to submit entire exam
    public function submitEntireExam(int $studentId, int $examTrainingId, array $answers): array
    {
        return DB::transaction(function () use ($studentId, $examTrainingId, $answers) {
            $examTraining = ExamTraining::with('questions.options')->findOrFail($examTrainingId);

            // Check if exam is locked
            if ($examTraining->isLocked()) {
                throw new \Exception(__('messages.exam_locked'));
            }

            // Check if student already took this exam
            if ($this->hasStudentTakenExam($studentId, $examTrainingId)) {
                throw new \Exception(__('messages.exam_already_taken'));
            }

            $totalXp = 0;
            $totalCoins = 0;
            $correctAnswers = 0;
            $totalQuestions = count($answers);
            $processedAnswers = [];

            foreach ($answers as $answerData) {
                $question = $examTraining->questions->firstWhere('id', $answerData['question_id']);

                if (!$question) {
                    continue;
                }

                $result = $this->evaluateAnswer(
                    $question,
                    $answerData['selections'] ?? [],
                    $answerData['user_answer'] ?? null
                );

                // Create answer record
                $answer = $this->create([
                    'student_id' => $studentId,
                    'question_id' => $answerData['question_id'],
                    'user_answer' => $answerData['user_answer'] ?? null,
                    'gained_xp' => $result['gained_xp'],
                    'gained_coins' => $result['gained_coins'],
                    'submitted_at' => now(),
                ]);

                // Create answer selections (not for written questions)
                if ($question->type->value !== 'written') {
                    foreach ($result['selections'] as $selection) {
                        AnswerSelection::create([
                            'answer_id' => $answer->id,
                            'option_id' => $selection['option_id'],
                            'is_correct' => $selection['is_correct'],
                            'gained_xp' => $selection['gained_xp'],
                            'gained_coins' => $selection['gained_coins'],
                            'order' => $selection['order'] ?? null,
                        ]);
                    }
                }

                $totalXp += $result['gained_xp'];
                $totalCoins += $result['gained_coins'];

                if ($result['is_correct']) {
                    $correctAnswers++;
                }

                $processedAnswers[] = [
                    'answer' => $answer,
                    'is_correct' => $result['is_correct'],
                    'gained_xp' => $result['gained_xp'],
                    'gained_coins' => $result['gained_coins'],
                ];
            }

            // Update student xp and coins
            $student = Student::findOrFail($studentId);
            $student->xp += $totalXp;
            $student->coins += $totalCoins;
            $student->save();

            // Lock exam after duration if applicable
            if ($examTraining->duration && !$examTraining->locked_after_duration) {
                $examTraining->locked_after_duration = Carbon::now()->addMinutes($examTraining->duration);
                $examTraining->save();
            }

            return [
                'total_xp' => $totalXp,
                'total_coins' => $totalCoins,
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'percentage' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0,
                'answers' => $processedAnswers,
            ];
        });
    }

    // <CHANGE> Check if student has taken exam
    public function hasStudentTakenExam(int $studentId, int $examTrainingId): bool
    {
        $examTraining = ExamTraining::findOrFail($examTrainingId);
        $questionIds = $examTraining->questions->pluck('id');

        return Answer::where('student_id', $studentId)
            ->whereIn('question_id', $questionIds)
            ->exists();
    }

    private function evaluateAnswer(Question $question, array $selections, ?string $userAnswer = null): array
    {
        $type = $question->type->value;

        // <CHANGE> Handle written questions
        if ($type === 'written') {
            return [
                'is_correct' => false, // Must be manually graded
                'gained_xp' => 0,
                'gained_coins' => 0,
                'selections' => [],
            ];
        }

        return match ($type) {
            'choice', 'true_false' => $this->evaluateChoiceOrTrueFalse($question, $selections),
            'connect' => $this->evaluateConnect($question, $selections),
            'arrange' => $this->evaluateArrange($question, $selections),
            default => ['is_correct' => false, 'gained_xp' => 0, 'gained_coins' => 0, 'selections' => []],
        };
    }

    private function evaluateChoiceOrTrueFalse(Question $question, array $selections): array
    {
        $selectedOptionId = $selections[0]['option_id'] ?? null;
        $option = QuestionOption::find($selectedOptionId);

        $isCorrect = $option && $option->is_correct;
        $gainedXp = $isCorrect ? $question->xp : 0;
        $gainedCoins = $isCorrect ? $question->coins : 0;

        return [
            'is_correct' => $isCorrect,
            'gained_xp' => $gainedXp,
            'gained_coins' => $gainedCoins,
            'selections' => [[
                'option_id' => $selectedOptionId,
                'is_correct' => $isCorrect,
                'gained_xp' => $gainedXp,
                'gained_coins' => $gainedCoins,
            ]],
        ];
    }

    private function evaluateConnect(Question $question, array $selections): array
    {
        $totalXp = 0;
        $totalCoins = 0;
        $allCorrect = true;
        $evaluatedSelections = [];

        foreach ($selections as $selection) {
            $leftOptionId = $selection['left_option_id'];
            $rightOptionId = $selection['right_option_id'];

            $leftOption = QuestionOption::find($leftOptionId);
            $isCorrect = $leftOption && $leftOption->match_id == $rightOptionId;

            if (!$isCorrect) {
                $allCorrect = false;
            }

            $gainedXp = $isCorrect ? ($leftOption->xp ?? 0) : 0;
            $gainedCoins = $isCorrect ? ($leftOption->coins ?? 0) : 0;

            $totalXp += $gainedXp;
            $totalCoins += $gainedCoins;

            $evaluatedSelections[] = [
                'option_id' => $leftOptionId,
                'is_correct' => $isCorrect,
                'gained_xp' => $gainedXp,
                'gained_coins' => $gainedCoins,
            ];
        }

        return [
            'is_correct' => $allCorrect,
            'gained_xp' => $totalXp,
            'gained_coins' => $totalCoins,
            'selections' => $evaluatedSelections,
        ];
    }

    private function evaluateArrange(Question $question, array $selections): array
    {
        $allCorrect = true;
        $totalXp = 0;
        $totalCoins = 0;
        $evaluatedSelections = [];

        foreach ($selections as $selection) {
            $option = QuestionOption::find($selection['option_id']);
            $isCorrect = $option && $option->arrange_order == $selection['order'];

            if (!$isCorrect) {
                $allCorrect = false;
            }

            $gainedXp = $isCorrect ? ($option->xp ?? 0) : 0;
            $gainedCoins = $isCorrect ? ($option->coins ?? 0) : 0;

            $totalXp += $gainedXp;
            $totalCoins += $gainedCoins;

            $evaluatedSelections[] = [
                'option_id' => $selection['option_id'],
                'is_correct' => $isCorrect,
                'gained_xp' => $gainedXp,
                'gained_coins' => $gainedCoins,
                'order' => $selection['order'],
            ];
        }

        if ($allCorrect) {
            $totalXp += $question->xp;
            $totalCoins += $question->coins;
        }

        return [
            'is_correct' => $allCorrect,
            'gained_xp' => $totalXp,
            'gained_coins' => $totalCoins,
            'selections' => $evaluatedSelections,
        ];
    }
}