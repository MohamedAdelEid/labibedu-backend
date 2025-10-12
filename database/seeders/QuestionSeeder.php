<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\QuestionOptionPair;
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
            'marks' => 2,
            'language' => 'en',
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
            'marks' => 1,
            'language' => 'en',
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
            'marks' => 3,
            'language' => 'en',
        ]);

        $left1 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Square', 'side' => 'left']);
        $right1 = QuestionOption::create(['question_id' => $q3->id, 'text' => '4 equal sides', 'side' => 'right']);
        QuestionOptionPair::create([
            'left_option_id' => $left1->id,
            'right_option_id' => $right1->id,
            'xp' => 5,
            'coins' => 3,
            'marks' => 1,
        ]);

        $left2 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Circle', 'side' => 'left']);
        $right2 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'No corners', 'side' => 'right']);
        QuestionOptionPair::create([
            'left_option_id' => $left2->id,
            'right_option_id' => $right2->id,
            'xp' => 5,
            'coins' => 3,
            'marks' => 1,
        ]);

        $left3 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Triangle', 'side' => 'left']);
        $right3 = QuestionOption::create(['question_id' => $q3->id, 'text' => '3 sides', 'side' => 'right']);
        QuestionOptionPair::create([
            'left_option_id' => $left3->id,
            'right_option_id' => $right3->id,
            'xp' => 5,
            'coins' => 2,
            'marks' => 1,
        ]);

        // Exam 1: Mathematics - Arrange Question
        $q4 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Arrange the following numbers in ascending order',
            'type' => 'arrange',
            'xp' => 12,
            'coins' => 6,
            'marks' => 2,
            'language' => 'en',
        ]);

        QuestionOption::create(['question_id' => $q4->id, 'text' => '3', 'arrange_order' => 1]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '7', 'arrange_order' => 2]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '15', 'arrange_order' => 3]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '22', 'arrange_order' => 4]);

        // Exam 1: Mathematics - Written Question
        $q5 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Explain the Pythagorean theorem and provide an example of its application.',
            'type' => 'written',
            'xp' => 20,
            'coins' => 10,
            'marks' => 5,
            'language' => 'en',
        ]);

        // Training 1: Algebra - Choice Question
        $q6 = Question::create([
            'exam_training_id' => 2,
            'title' => 'Simplify: 3x + 2x',
            'type' => 'choice',
            'xp' => 8,
            'coins' => 4,
            'marks' => 1,
            'language' => 'en',
        ]);

        QuestionOption::create(['question_id' => $q6->id, 'text' => '5x', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => '6x', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => '5x²', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => '3x²', 'is_correct' => false]);
    }
}
