<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\ExamAttempt;
use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerOrder;
use App\Infrastructure\Models\AnswerPair;
use App\Infrastructure\Models\AnswerGrade;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use Carbon\Carbon;

class ExamAttemptAnswerSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // SCENARIO 1: Student 1 - Finished Exam 1 (Mathematics Midterm)
        // ============================================
        $attempt1 = ExamAttempt::create([
            'student_id' => 1,
            'exam_training_id' => 1, // Mathematics Midterm Exam
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->subHours(1)->subMinutes(30),
            'remaining_seconds' => 0,
            'status' => 'finished',
        ]);

        // Question 1: Choice - CORRECT answer
        $q1CorrectOption = QuestionOption::where('question_id', 1)
            ->where('is_correct', true)
            ->first();
        
        $answer1 = Answer::create([
            'student_id' => 1,
            'question_id' => 1,
            'option_id' => $q1CorrectOption->id,
            'submitted_at' => Carbon::now()->subHours(1)->subMinutes(50),
        ]);

        AnswerGrade::create([
            'answer_id' => $answer1->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 10,
            'gained_coins' => 5,
            'gained_marks' => 2,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(1),
        ]);

        // Question 2: True/False - CORRECT answer
        $q2TrueOption = QuestionOption::where('question_id', 2)
            ->where('is_correct', true)
            ->first();
        
        $answer2 = Answer::create([
            'student_id' => 1,
            'question_id' => 2,
            'option_id' => $q2TrueOption->id,
            'true_false_answer' => true,
            'submitted_at' => Carbon::now()->subHours(1)->subMinutes(48),
        ]);

        AnswerGrade::create([
            'answer_id' => $answer2->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 5,
            'gained_coins' => 3,
            'gained_marks' => 1,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(1),
        ]);

        // Question 3: Connect - PARTIALLY CORRECT (2 out of 3 pairs correct)
        $q3Options = QuestionOption::where('question_id', 3)->get();
        $leftOptions = $q3Options->where('side', 'left');
        $rightOptions = $q3Options->where('side', 'right');

        $answer3 = Answer::create([
            'student_id' => 1,
            'question_id' => 3,
            'submitted_at' => Carbon::now()->subHours(1)->subMinutes(45),
        ]);

        // Correct pair 1: Square -> 4 equal sides
        AnswerPair::create([
            'answer_id' => $answer3->id,
            'left_option_id' => $leftOptions->where('text', 'Square')->first()->id,
            'right_option_id' => $rightOptions->where('text', '4 equal sides')->first()->id,
        ]);

        // Correct pair 2: Circle -> No corners
        AnswerPair::create([
            'answer_id' => $answer3->id,
            'left_option_id' => $leftOptions->where('text', 'Circle')->first()->id,
            'right_option_id' => $rightOptions->where('text', 'No corners')->first()->id,
        ]);

        // WRONG pair 3: Triangle -> 4 equal sides (should be "3 sides")
        AnswerPair::create([
            'answer_id' => $answer3->id,
            'left_option_id' => $leftOptions->where('text', 'Triangle')->first()->id,
            'right_option_id' => $rightOptions->where('text', '4 equal sides')->first()->id,
        ]);

        AnswerGrade::create([
            'answer_id' => $answer3->id,
            'graded_by' => 1,
            'is_correct' => false, // Not fully correct
            'gained_xp' => 10, // Partial credit
            'gained_coins' => 6,
            'gained_marks' => 2,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(1),
        ]);

        // Question 4: Arrange - WRONG order
        $q4Options = QuestionOption::where('question_id', 4)->get();
        
        $answer4 = Answer::create([
            'student_id' => 1,
            'question_id' => 4,
            'submitted_at' => Carbon::now()->subHours(1)->subMinutes(42),
        ]);

        // Wrong order: 7, 3, 15, 22 (should be 3, 7, 15, 22)
        AnswerOrder::create([
            'answer_id' => $answer4->id,
            'option_id' => $q4Options->where('text', '7')->first()->id,
            'order' => 1,
        ]);
        AnswerOrder::create([
            'answer_id' => $answer4->id,
            'option_id' => $q4Options->where('text', '3')->first()->id,
            'order' => 2,
        ]);
        AnswerOrder::create([
            'answer_id' => $answer4->id,
            'option_id' => $q4Options->where('text', '15')->first()->id,
            'order' => 3,
        ]);
        AnswerOrder::create([
            'answer_id' => $answer4->id,
            'option_id' => $q4Options->where('text', '22')->first()->id,
            'order' => 4,
        ]);

        AnswerGrade::create([
            'answer_id' => $answer4->id,
            'graded_by' => 1,
            'is_correct' => false,
            'gained_xp' => 0,
            'gained_coins' => 0,
            'gained_marks' => 0,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(1),
        ]);

        // Question 5: Written - Needs manual grading
        $answer5 = Answer::create([
            'student_id' => 1,
            'question_id' => 5,
            'user_answer' => 'The Pythagorean theorem states that in a right triangle, the square of the hypotenuse equals the sum of squares of the other two sides (a² + b² = c²). For example, if a triangle has sides of 3 and 4, the hypotenuse would be 5.',
            'submitted_at' => Carbon::now()->subHours(1)->subMinutes(35),
        ]);

        AnswerGrade::create([
            'answer_id' => $answer5->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 18,
            'gained_coins' => 9,
            'gained_marks' => 4,
            'feedback' => 'Excellent explanation with a clear example. Minor deduction for not mentioning it only applies to right triangles explicitly.',
            'status' => 'graded',
            'graded_at' => Carbon::now()->subMinutes(30),
        ]);

        // ============================================
        // SCENARIO 2: Student 2 - In-Progress Exam 1
        // ============================================
        $attempt2 = ExamAttempt::create([
            'student_id' => 2,
            'exam_training_id' => 1,
            'start_time' => Carbon::now()->subMinutes(30),
            'end_time' => null,
            'remaining_seconds' => 3600, // 60 minutes remaining
            'status' => 'in_progress',
        ]);

        // Only answered 2 questions so far

        // Question 1: Choice - WRONG answer
        $wrongOption = QuestionOption::where('question_id', 1)
            ->where('text', '10')
            ->first();
        
        $answer6 = Answer::create([
            'student_id' => 2,
            'question_id' => 1,
            'option_id' => $wrongOption->id,
            'submitted_at' => Carbon::now()->subMinutes(28),
        ]);

        // No grade yet (in progress)

        // Question 2: True/False - CORRECT answer
        $answer7 = Answer::create([
            'student_id' => 2,
            'question_id' => 2,
            'option_id' => $q2TrueOption->id,
            'true_false_answer' => true,
            'submitted_at' => Carbon::now()->subMinutes(25),
        ]);

        // No grade yet (in progress)

        // ============================================
        // SCENARIO 3: Student 1 - Finished Training 1 (Algebra Practice)
        // ============================================
        $attempt3 = ExamAttempt::create([
            'student_id' => 1,
            'exam_training_id' => 4, // Algebra Practice Training
            'start_time' => Carbon::now()->subDays(1),
            'end_time' => Carbon::now()->subDays(1)->addMinutes(15),
            'remaining_seconds' => 0,
            'status' => 'finished',
        ]);

        // Question 16: Choice - CORRECT answer (Simplify: 3x + 2x = 5x)
        $q16CorrectOption = QuestionOption::where('question_id', 16)
            ->where('is_correct', true)
            ->first();
        
        $answer8 = Answer::create([
            'student_id' => 1,
            'question_id' => 16,
            'option_id' => $q16CorrectOption->id,
            'submitted_at' => Carbon::now()->subDays(1)->addMinutes(5),
        ]);

        AnswerGrade::create([
            'answer_id' => $answer8->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 8,
            'gained_coins' => 4,
            'gained_marks' => 1,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subDays(1)->addMinutes(20),
        ]);

        // ============================================
        // SCENARIO 4: Student 3 - Finished Exam 1 with all CORRECT answers
        // ============================================
        $attempt4 = ExamAttempt::create([
            'student_id' => 3,
            'exam_training_id' => 1,
            'start_time' => Carbon::now()->subHours(5),
            'end_time' => Carbon::now()->subHours(4),
            'remaining_seconds' => 0,
            'status' => 'finished',
        ]);

        // Question 1: Choice - CORRECT
        $answer9 = Answer::create([
            'student_id' => 3,
            'question_id' => 1,
            'option_id' => $q1CorrectOption->id,
            'submitted_at' => Carbon::now()->subHours(4)->subMinutes(50),
        ]);

        AnswerGrade::create([
            'answer_id' => $answer9->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 10,
            'gained_coins' => 5,
            'gained_marks' => 2,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(3)->subMinutes(30),
        ]);

        // Question 2: True/False - CORRECT
        $answer10 = Answer::create([
            'student_id' => 3,
            'question_id' => 2,
            'option_id' => $q2TrueOption->id,
            'true_false_answer' => true,
            'submitted_at' => Carbon::now()->subHours(4)->subMinutes(48),
        ]);

        AnswerGrade::create([
            'answer_id' => $answer10->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 5,
            'gained_coins' => 3,
            'gained_marks' => 1,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(3)->subMinutes(30),
        ]);

        // Question 3: Connect - ALL CORRECT
        $answer11 = Answer::create([
            'student_id' => 3,
            'question_id' => 3,
            'submitted_at' => Carbon::now()->subHours(4)->subMinutes(45),
        ]);

        // All correct pairs
        AnswerPair::create([
            'answer_id' => $answer11->id,
            'left_option_id' => $leftOptions->where('text', 'Square')->first()->id,
            'right_option_id' => $rightOptions->where('text', '4 equal sides')->first()->id,
        ]);
        
        AnswerPair::create([
            'answer_id' => $answer11->id,
            'left_option_id' => $leftOptions->where('text', 'Circle')->first()->id,
            'right_option_id' => $rightOptions->where('text', 'No corners')->first()->id,
        ]);
        
        AnswerPair::create([
            'answer_id' => $answer11->id,
            'left_option_id' => $leftOptions->where('text', 'Triangle')->first()->id,
            'right_option_id' => $rightOptions->where('text', '3 sides')->first()->id,
        ]);

        AnswerGrade::create([
            'answer_id' => $answer11->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 15,
            'gained_coins' => 8,
            'gained_marks' => 3,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(3)->subMinutes(30),
        ]);

        // Question 4: Arrange - CORRECT order
        $answer12 = Answer::create([
            'student_id' => 3,
            'question_id' => 4,
            'submitted_at' => Carbon::now()->subHours(4)->subMinutes(40),
        ]);

        // Correct order: 3, 7, 15, 22
        AnswerOrder::create([
            'answer_id' => $answer12->id,
            'option_id' => $q4Options->where('text', '3')->first()->id,
            'order' => 1,
        ]);
        AnswerOrder::create([
            'answer_id' => $answer12->id,
            'option_id' => $q4Options->where('text', '7')->first()->id,
            'order' => 2,
        ]);
        AnswerOrder::create([
            'answer_id' => $answer12->id,
            'option_id' => $q4Options->where('text', '15')->first()->id,
            'order' => 3,
        ]);
        AnswerOrder::create([
            'answer_id' => $answer12->id,
            'option_id' => $q4Options->where('text', '22')->first()->id,
            'order' => 4,
        ]);

        AnswerGrade::create([
            'answer_id' => $answer12->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 12,
            'gained_coins' => 6,
            'gained_marks' => 2,
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(3)->subMinutes(30),
        ]);

        // Question 5: Written - Perfect answer
        $answer13 = Answer::create([
            'student_id' => 3,
            'question_id' => 5,
            'user_answer' => 'The Pythagorean theorem is a fundamental principle in geometry that applies to right triangles. It states that a² + b² = c², where c is the hypotenuse and a and b are the other two sides. Example: In a right triangle with sides 5 and 12, the hypotenuse is √(5² + 12²) = √(25 + 144) = √169 = 13.',
            'submitted_at' => Carbon::now()->subHours(4)->subMinutes(30),
        ]);

        AnswerGrade::create([
            'answer_id' => $answer13->id,
            'graded_by' => 1,
            'is_correct' => true,
            'gained_xp' => 20,
            'gained_coins' => 10,
            'gained_marks' => 5,
            'feedback' => 'Perfect answer with clear explanation and detailed example showing the calculation.',
            'status' => 'graded',
            'graded_at' => Carbon::now()->subHours(3),
        ]);

        $this->command->info('✅ Exam attempts and answers seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   - 4 exam attempts (2 finished, 1 in-progress, 1 perfect score)');
        $this->command->info('   - 13 answers covering all question types');
        $this->command->info('   - Mix of correct and incorrect answers');
        $this->command->info('   - Answer grades for finished attempts');
    }
}