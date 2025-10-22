<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Services\QuestionServiceInterface;
use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerGrade;

class QuestionService implements QuestionServiceInterface
{
    /**
     * Get question statistics for a student
     */
    public function getStudentQuestionStatistics(int $studentId): array
    {
        $answers = Answer::where('student_id', $studentId)->get();

        $totalSolved = $answers->count();
        $correct = 0;
        $wrong = 0;

        foreach ($answers as $answer) {
            $grade = $answer->grade;
            if ($grade) {
                if ($grade->is_correct) {
                    $correct++;
                } else {
                    $wrong++;
                }
            }
        }

        return [
            'solved' => $totalSolved,
            'correct' => $correct,
            'wrong' => $wrong,
        ];
    }
}
