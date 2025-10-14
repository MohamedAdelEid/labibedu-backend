<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\QuestionOptionPair;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // EXAM 1: Mathematics Midterm (All question types)
        // ============================================
        
        // Question 1: Choice
        $q1 = Question::create([
            'exam_training_id' => 1,
            'title' => 'What is the value of x in the equation 2x + 5 = 15?',
            'type' => 'choice',
            'xp' => 10,
            'coins' => 5,
            'marks' => 2,
        ]);

        QuestionOption::create(['question_id' => $q1->id, 'text' => '5', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q1->id, 'text' => '10', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'text' => '7', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'text' => '3', 'is_correct' => false]);

        // Question 2: True/False
        $q2 = Question::create([
            'exam_training_id' => 1,
            'title' => 'The sum of angles in a triangle is 180 degrees.',
            'type' => 'true_false',
            'xp' => 5,
            'coins' => 3,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q2->id, 'text' => 'True', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q2->id, 'text' => 'False', 'is_correct' => false]);

        // Question 3: Connect
        $q3 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Match the geometric shapes with their properties',
            'type' => 'connect',
            'xp' => 15,
            'coins' => 8,
            'marks' => 3,
        ]);

        $left1 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Square', 'side' => 'left']);
        $right1 = QuestionOption::create(['question_id' => $q3->id, 'text' => '4 equal sides', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left1->id, 'right_option_id' => $right1->id, 'xp' => 5, 'coins' => 3, 'marks' => 1]);

        $left2 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Circle', 'side' => 'left']);
        $right2 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'No corners', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left2->id, 'right_option_id' => $right2->id, 'xp' => 5, 'coins' => 3, 'marks' => 1]);

        $left3 = QuestionOption::create(['question_id' => $q3->id, 'text' => 'Triangle', 'side' => 'left']);
        $right3 = QuestionOption::create(['question_id' => $q3->id, 'text' => '3 sides', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left3->id, 'right_option_id' => $right3->id, 'xp' => 5, 'coins' => 2, 'marks' => 1]);

        // Question 4: Arrange
        $q4 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Arrange the following numbers in ascending order',
            'type' => 'arrange',
            'xp' => 12,
            'coins' => 6,
            'marks' => 2,
        ]);

        QuestionOption::create(['question_id' => $q4->id, 'text' => '3', 'arrange_order' => 1]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '7', 'arrange_order' => 2]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '15', 'arrange_order' => 3]);
        QuestionOption::create(['question_id' => $q4->id, 'text' => '22', 'arrange_order' => 4]);

        // Question 5: Written
        $q5 = Question::create([
            'exam_training_id' => 1,
            'title' => 'Explain the Pythagorean theorem and provide an example of its application.',
            'type' => 'written',
            'xp' => 20,
            'coins' => 10,
            'marks' => 5,
        ]);

        // ============================================
        // EXAM 2: Science Final (All question types)
        // ============================================

        // Question 6: Choice
        $q6 = Question::create([
            'exam_training_id' => 2,
            'title' => 'What is the chemical symbol for water?',
            'type' => 'choice',
            'xp' => 8,
            'coins' => 4,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q6->id, 'text' => 'H2O', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => 'CO2', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => 'O2', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q6->id, 'text' => 'H2', 'is_correct' => false]);

        // Question 7: True/False
        $q7 = Question::create([
            'exam_training_id' => 2,
            'title' => 'The speed of light is faster than the speed of sound.',
            'type' => 'true_false',
            'xp' => 6,
            'coins' => 3,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q7->id, 'text' => 'True', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q7->id, 'text' => 'False', 'is_correct' => false]);

        // Question 8: Connect
        $q8 = Question::create([
            'exam_training_id' => 2,
            'title' => 'Match the scientists with their discoveries',
            'type' => 'connect',
            'xp' => 18,
            'coins' => 9,
            'marks' => 3,
        ]);

        $left4 = QuestionOption::create(['question_id' => $q8->id, 'text' => 'Newton', 'side' => 'left']);
        $right4 = QuestionOption::create(['question_id' => $q8->id, 'text' => 'Gravity', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left4->id, 'right_option_id' => $right4->id, 'xp' => 6, 'coins' => 3, 'marks' => 1]);

        $left5 = QuestionOption::create(['question_id' => $q8->id, 'text' => 'Einstein', 'side' => 'left']);
        $right5 = QuestionOption::create(['question_id' => $q8->id, 'text' => 'Relativity', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left5->id, 'right_option_id' => $right5->id, 'xp' => 6, 'coins' => 3, 'marks' => 1]);

        $left6 = QuestionOption::create(['question_id' => $q8->id, 'text' => 'Darwin', 'side' => 'left']);
        $right6 = QuestionOption::create(['question_id' => $q8->id, 'text' => 'Evolution', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left6->id, 'right_option_id' => $right6->id, 'xp' => 6, 'coins' => 3, 'marks' => 1]);

        // Question 9: Arrange
        $q9 = Question::create([
            'exam_training_id' => 2,
            'title' => 'Arrange the planets in order from the Sun',
            'type' => 'arrange',
            'xp' => 15,
            'coins' => 7,
            'marks' => 2,
        ]);

        QuestionOption::create(['question_id' => $q9->id, 'text' => 'Mercury', 'arrange_order' => 1]);
        QuestionOption::create(['question_id' => $q9->id, 'text' => 'Venus', 'arrange_order' => 2]);
        QuestionOption::create(['question_id' => $q9->id, 'text' => 'Earth', 'arrange_order' => 3]);
        QuestionOption::create(['question_id' => $q9->id, 'text' => 'Mars', 'arrange_order' => 4]);

        // Question 10: Written
        $q10 = Question::create([
            'exam_training_id' => 2,
            'title' => 'Describe the process of photosynthesis and its importance.',
            'type' => 'written',
            'xp' => 25,
            'coins' => 12,
            'marks' => 5,
        ]);

        // ============================================
        // EXAM 3: English Grammar (All question types)
        // ============================================

        // Question 11: Choice
        $q11 = Question::create([
            'exam_training_id' => 3,
            'title' => 'Which sentence is grammatically correct?',
            'type' => 'choice',
            'xp' => 10,
            'coins' => 5,
            'marks' => 2,
        ]);

        QuestionOption::create(['question_id' => $q11->id, 'text' => 'She has been working here for five years.', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q11->id, 'text' => 'She have been working here for five years.', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q11->id, 'text' => 'She has working here for five years.', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q11->id, 'text' => 'She been working here for five years.', 'is_correct' => false]);

        // Question 12: True/False
        $q12 = Question::create([
            'exam_training_id' => 3,
            'title' => 'The past tense of "go" is "went".',
            'type' => 'true_false',
            'xp' => 5,
            'coins' => 3,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q12->id, 'text' => 'True', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q12->id, 'text' => 'False', 'is_correct' => false]);

        // Question 13: Connect
        $q13 = Question::create([
            'exam_training_id' => 3,
            'title' => 'Match the words with their synonyms',
            'type' => 'connect',
            'xp' => 12,
            'coins' => 6,
            'marks' => 2,
        ]);

        $left7 = QuestionOption::create(['question_id' => $q13->id, 'text' => 'Happy', 'side' => 'left']);
        $right7 = QuestionOption::create(['question_id' => $q13->id, 'text' => 'Joyful', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left7->id, 'right_option_id' => $right7->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        $left8 = QuestionOption::create(['question_id' => $q13->id, 'text' => 'Angry', 'side' => 'left']);
        $right8 = QuestionOption::create(['question_id' => $q13->id, 'text' => 'Furious', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left8->id, 'right_option_id' => $right8->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        $left9 = QuestionOption::create(['question_id' => $q13->id, 'text' => 'Big', 'side' => 'left']);
        $right9 = QuestionOption::create(['question_id' => $q13->id, 'text' => 'Large', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left9->id, 'right_option_id' => $right9->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        // Question 14: Arrange
        $q14 = Question::create([
            'exam_training_id' => 3,
            'title' => 'Arrange the words to form a correct sentence',
            'type' => 'arrange',
            'xp' => 10,
            'coins' => 5,
            'marks' => 2,
        ]);

        QuestionOption::create(['question_id' => $q14->id, 'text' => 'I', 'arrange_order' => 1]);
        QuestionOption::create(['question_id' => $q14->id, 'text' => 'love', 'arrange_order' => 2]);
        QuestionOption::create(['question_id' => $q14->id, 'text' => 'learning', 'arrange_order' => 3]);
        QuestionOption::create(['question_id' => $q14->id, 'text' => 'English', 'arrange_order' => 4]);

        // Question 15: Written
        $q15 = Question::create([
            'exam_training_id' => 3,
            'title' => 'Write a short paragraph about your favorite hobby.',
            'type' => 'written',
            'xp' => 20,
            'coins' => 10,
            'marks' => 5,
        ]);

        // ============================================
        // TRAINING 1: Algebra Practice (All question types)
        // ============================================

        // Question 16: Choice
        $q16 = Question::create([
            'exam_training_id' => 4,
            'title' => 'Simplify: 3x + 2x',
            'type' => 'choice',
            'xp' => 8,
            'coins' => 4,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q16->id, 'text' => '5x', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q16->id, 'text' => '6x', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q16->id, 'text' => '5x²', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q16->id, 'text' => '3x²', 'is_correct' => false]);

        // Question 17: True/False
        $q17 = Question::create([
            'exam_training_id' => 4,
            'title' => 'The equation x + 5 = 10 has the solution x = 5.',
            'type' => 'true_false',
            'xp' => 5,
            'coins' => 3,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q17->id, 'text' => 'True', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q17->id, 'text' => 'False', 'is_correct' => false]);

        // Question 18: Connect
        $q18 = Question::create([
            'exam_training_id' => 4,
            'title' => 'Match the algebraic expressions with their simplified forms',
            'type' => 'connect',
            'xp' => 12,
            'coins' => 6,
            'marks' => 2,
        ]);

        $left10 = QuestionOption::create(['question_id' => $q18->id, 'text' => '2x + 3x', 'side' => 'left']);
        $right10 = QuestionOption::create(['question_id' => $q18->id, 'text' => '5x', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left10->id, 'right_option_id' => $right10->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        $left11 = QuestionOption::create(['question_id' => $q18->id, 'text' => '4x - x', 'side' => 'left']);
        $right11 = QuestionOption::create(['question_id' => $q18->id, 'text' => '3x', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left11->id, 'right_option_id' => $right11->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        // Question 19: Arrange
        $q19 = Question::create([
            'exam_training_id' => 4,
            'title' => 'Arrange the steps to solve: 2x + 4 = 10',
            'type' => 'arrange',
            'xp' => 10,
            'coins' => 5,
            'marks' => 2,
        ]);

        QuestionOption::create(['question_id' => $q19->id, 'text' => 'Subtract 4 from both sides', 'arrange_order' => 1]);
        QuestionOption::create(['question_id' => $q19->id, 'text' => 'Get 2x = 6', 'arrange_order' => 2]);
        QuestionOption::create(['question_id' => $q19->id, 'text' => 'Divide both sides by 2', 'arrange_order' => 3]);
        QuestionOption::create(['question_id' => $q19->id, 'text' => 'Solution: x = 3', 'arrange_order' => 4]);

        // Question 20: Written
        $q20 = Question::create([
            'exam_training_id' => 4,
            'title' => 'Explain how to solve a linear equation with one variable.',
            'type' => 'written',
            'xp' => 15,
            'coins' => 8,
            'marks' => 3,
        ]);

        // ============================================
        // TRAINING 2: Physics Fundamentals (All question types)
        // ============================================

        // Question 21: Choice
        $q21 = Question::create([
            'exam_training_id' => 5,
            'title' => 'What is the SI unit of force?',
            'type' => 'choice',
            'xp' => 8,
            'coins' => 4,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q21->id, 'text' => 'Newton', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q21->id, 'text' => 'Joule', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q21->id, 'text' => 'Watt', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q21->id, 'text' => 'Pascal', 'is_correct' => false]);

        // Question 22: True/False
        $q22 = Question::create([
            'exam_training_id' => 5,
            'title' => 'Energy can be created or destroyed.',
            'type' => 'true_false',
            'xp' => 6,
            'coins' => 3,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q22->id, 'text' => 'True', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q22->id, 'text' => 'False', 'is_correct' => true]);

        // Question 23: Connect
        $q23 = Question::create([
            'exam_training_id' => 5,
            'title' => 'Match the physical quantities with their units',
            'type' => 'connect',
            'xp' => 12,
            'coins' => 6,
            'marks' => 2,
        ]);

        $left12 = QuestionOption::create(['question_id' => $q23->id, 'text' => 'Velocity', 'side' => 'left']);
        $right12 = QuestionOption::create(['question_id' => $q23->id, 'text' => 'm/s', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left12->id, 'right_option_id' => $right12->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        $left13 = QuestionOption::create(['question_id' => $q23->id, 'text' => 'Acceleration', 'side' => 'left']);
        $right13 = QuestionOption::create(['question_id' => $q23->id, 'text' => 'm/s²', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left13->id, 'right_option_id' => $right13->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        // Question 24: Arrange
        $q24 = Question::create([
            'exam_training_id' => 5,
            'title' => 'Arrange the states of matter by increasing particle movement',
            'type' => 'arrange',
            'xp' => 10,
            'coins' => 5,
            'marks' => 2,
        ]);

        QuestionOption::create(['question_id' => $q24->id, 'text' => 'Solid', 'arrange_order' => 1]);
        QuestionOption::create(['question_id' => $q24->id, 'text' => 'Liquid', 'arrange_order' => 2]);
        QuestionOption::create(['question_id' => $q24->id, 'text' => 'Gas', 'arrange_order' => 3]);

        // Question 25: Written
        $q25 = Question::create([
            'exam_training_id' => 5,
            'title' => 'Explain Newton\'s First Law of Motion with an example.',
            'type' => 'written',
            'xp' => 15,
            'coins' => 8,
            'marks' => 3,
        ]);

        // ============================================
        // TRAINING 3: English Vocabulary (All question types)
        // ============================================

        // Question 26: Choice
        $q26 = Question::create([
            'exam_training_id' => 6,
            'title' => 'What is the meaning of "benevolent"?',
            'type' => 'choice',
            'xp' => 8,
            'coins' => 4,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q26->id, 'text' => 'Kind and generous', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q26->id, 'text' => 'Cruel and harsh', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q26->id, 'text' => 'Lazy and unmotivated', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q26->id, 'text' => 'Angry and upset', 'is_correct' => false]);

        // Question 27: True/False
        $q27 = Question::create([
            'exam_training_id' => 6,
            'title' => 'An antonym is a word that has the opposite meaning of another word.',
            'type' => 'true_false',
            'xp' => 5,
            'coins' => 3,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q27->id, 'text' => 'True', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q27->id, 'text' => 'False', 'is_correct' => false]);

        // Question 28: Connect
        $q28 = Question::create([
            'exam_training_id' => 6,
            'title' => 'Match the words with their antonyms',
            'type' => 'connect',
            'xp' => 12,
            'coins' => 6,
            'marks' => 2,
        ]);

        $left14 = QuestionOption::create(['question_id' => $q28->id, 'text' => 'Hot', 'side' => 'left']);
        $right14 = QuestionOption::create(['question_id' => $q28->id, 'text' => 'Cold', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left14->id, 'right_option_id' => $right14->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        $left15 = QuestionOption::create(['question_id' => $q28->id, 'text' => 'Fast', 'side' => 'left']);
        $right15 = QuestionOption::create(['question_id' => $q28->id, 'text' => 'Slow', 'side' => 'right']);
        QuestionOptionPair::create(['left_option_id' => $left15->id, 'right_option_id' => $right15->id, 'xp' => 4, 'coins' => 2, 'marks' => 1]);

        // Question 29: Arrange
        $q29 = Question::create([
            'exam_training_id' => 6,
            'title' => 'Arrange the words alphabetically',
            'type' => 'arrange',
            'xp' => 8,
            'coins' => 4,
            'marks' => 1,
        ]);

        QuestionOption::create(['question_id' => $q29->id, 'text' => 'Apple', 'arrange_order' => 1]);
        QuestionOption::create(['question_id' => $q29->id, 'text' => 'Banana', 'arrange_order' => 2]);
        QuestionOption::create(['question_id' => $q29->id, 'text' => 'Cherry', 'arrange_order' => 3]);
        QuestionOption::create(['question_id' => $q29->id, 'text' => 'Date', 'arrange_order' => 4]);

        // Question 30: Written
        $q30 = Question::create([
            'exam_training_id' => 6,
            'title' => 'Write a sentence using the word "perseverance".',
            'type' => 'written',
            'xp' => 12,
            'coins' => 6,
            'marks' => 2,
        ]);

        $this->command->info('✅ Questions seeded successfully!');
        $this->command->info('📊 Created: 30 questions (5 per exam/training, all question types)');
    }
}
