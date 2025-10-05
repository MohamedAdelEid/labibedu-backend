<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Exam 1: Mathematics - Choice Question
        $q1 = Question::create([
            'exam_training_id' => 1,
            'title' => 'What is the value of x in the equation 2x + 5 = 15?',
            'type' => 'choice',
            'xp' => 10,
            'coins' => 5,
        ]);

        QuestionOption::create(['question_id' => $q1->id, 'text' => '5', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q1->id, 'text' => '10', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'text' => '7', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'text' => '3', 'is_correct' => false]);

        // Exam 1: Mathematics - True/False Question
        $q2 = Question::create([
            'exam_training_id' => 1,
            'title' => 'The sum of angles in a triangle is 180 degrees.',
            'type' => 'true_false',
            'xp' => 5,
            'coins' => 3,
        ]);

        QuestionOption::create(['question_id' => $q2->id, 'text' => 'True', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q2->id, 'text' => 'False', 'is_correct' => false]);

        // Exam 1: Mathematics - Connect Question
        $q3 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Match the geometric shapes with their properties',
            'type' => 'connect',
            'xp' => 15,
            'coins' => 8,
        ]);

        $left1 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Square', 'side' => 'left', 'xp' => 5, 'coins' => 2]);
        $right1 = QuestionOption::create(['question_id' => $q3->id, 'text' => '4 equal sides', 'side' => 'right']);
        $left1->update(['match_id' => $right1->id]);

        $left2 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Circle', 'side' => 'left', 'xp' => 5, 'coins' => 2]);
        $right2 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'No corners', 'side' => 'right']);
        $left2->update(['match_id' => $right2->id]);

        $left3 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Triangle', 'side' => 'left', 'xp' => 5, 'coins' => 2]);
        $right3 = QuestionOption::create(['question_id' => $q3->id, 'text' => '3 sides', 'side' => 'right']);
        $left3->update(['match_id' => $right3->id]);

        // Exam 1: Mathematics - Arrange Question
        $q4 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Arrange the following numbers in ascending order',
            'type' => 'arrange',
            'xp' => 12,
            'coins' => 6,
        ]);

        QuestionOption::create(['question_id' => $q4->id, 'text' => '3', 'arrange_order' => 1, 'xp' => 3, 'coins' => 1]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '7', 'arrange_order' => 2, 'xp' => 3, 'coins' => 1]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '15', 'arrange_order' => 3, 'xp' => 3, 'coins' => 2]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '22', 'arrange_order' => 4, 'xp' => 3, 'coins' => 2]);

        // Exam 1: Mathematics - Written Question
        $q5 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Explain the Pythagorean theorem and provide an example of its application.',
            'type' => 'written',
            'xp' => 20,
            'coins' => 10,
        ]);

        // Training 1: Algebra - Choice Question
        $q6 = Question::create([
            'exam_training_id' => 2,
            'title' => 'Simplify: 3x + 2x',
            'type' => 'choice',
            'xp' => 8,
            'coins' => 4,
        ]);

        QuestionOption::create(['question_id' => $q6->id, 'text' => '5x', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => '6x', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => '5x²', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => '3x²', 'is_correct' => false]);

        // Exam 2: Science - Choice Question
        $q7 = Question::create([
            'exam_training_id' => 3,
            'title' => 'What is the chemical symbol for water?',
            'type' => 'choice',
            'xp' => 10,
            'coins' => 5,
        ]);

        QuestionOption::create(['question_id' => $q7->id, 'text' => 'H2O', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q7->id, 'text' => 'CO2', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q7->id, 'text' => 'O2', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q7->id, 'text' => 'H2', 'is_correct' => false]);

        // Exam 2: Science - Written Question
        $q8 = Question::create([
            'exam_training_id' => 3,
            'title' => 'Describe the process of photosynthesis in plants.',
            'type' => 'written',
            'xp' => 25,
            'coins' => 12,
        ]);

        // Training 2: English - True/False Question
        $q9 = Question::create([
            'exam_training_id' => 4,
            'title' => 'The past tense of "go" is "went".',
            'type' => 'true_false',
            'xp' => 5,
            'coins' => 3,
        ]);

        QuestionOption::create(['question_id' => $q9->id, 'text' => 'True', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q9->id, 'text' => 'False', 'is_correct' => false]);

        // Training 2: English - Written Question
        $q10 = Question::create([
            'exam_training_id' => 4,
            'title' => 'Write a short paragraph about your favorite hobby.',
            'type' => 'written',
            'xp' => 15,
            'coins' => 8,
        ]);
    }
}