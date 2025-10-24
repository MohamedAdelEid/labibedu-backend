<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\QuestionOptionPair;

class QuestionSeeder extends Seeder
{
    /**
     * Main seeder method
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Questions Seeding...');

        // Define all exam/training data
        $examTrainingData = $this->getExamTrainingData();

        // Create questions for each exam/training
        foreach ($examTrainingData as $examTraining) {
            $this->createExamTrainingQuestions($examTraining);
        }

        $this->command->info('✅ Questions seeded successfully!');
        $this->command->info('📊 Total questions created: ' . array_sum(array_column($examTrainingData, 'question_count')));
    }

    /**
     * Get all exam/training data configuration
     */
    private function getExamTrainingData(): array
    {
        return [
            // EXAM 1: Mathematics Midterm
            [
                'id' => 1,
                'name' => 'Mathematics Midterm',
                'type' => 'exam',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'What is the value of x in the equation 2x + 5 = 15?',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'en',
                        'options' => [
                            ['text' => '5', 'is_correct' => true],
                            ['text' => '10', 'is_correct' => false],
                            ['text' => '7', 'is_correct' => false],
                            ['text' => '3', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'The sum of angles in a triangle is 180 degrees.',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'Match the geometric shapes with their properties',
                        'type' => 'connect',
                        'xp' => 15,
                        'coins' => 8,
                        'marks' => 3,
                        'language' => 'en',
                        'pairs' => [
                            ['left' => 'Square', 'right' => '4 equal sides', 'xp' => 5, 'coins' => 3, 'marks' => 1],
                            ['left' => 'Circle', 'right' => 'No corners', 'xp' => 5, 'coins' => 3, 'marks' => 1],
                            ['left' => 'Triangle', 'right' => '3 sides', 'xp' => 5, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'Arrange the following numbers in ascending order',
                        'type' => 'arrange',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'en',
                        'options' => [
                            ['text' => '3', 'order' => 1],
                            ['text' => '7', 'order' => 2],
                            ['text' => '15', 'order' => 3],
                            ['text' => '22', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // EXAM 2: Science Final
            [
                'id' => 2,
                'name' => 'Science Final',
                'type' => 'exam',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'What is the chemical symbol for water?',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'H2O', 'is_correct' => true],
                            ['text' => 'CO2', 'is_correct' => false],
                            ['text' => 'O2', 'is_correct' => false],
                            ['text' => 'H2', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'The speed of light is faster than the speed of sound.',
                        'type' => 'true_false',
                        'xp' => 6,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'Match the scientists with their discoveries',
                        'type' => 'connect',
                        'xp' => 18,
                        'coins' => 9,
                        'marks' => 3,
                        'language' => 'en',
                        'pairs' => [
                            ['left' => 'Newton', 'right' => 'Gravity', 'xp' => 6, 'coins' => 3, 'marks' => 1],
                            ['left' => 'Einstein', 'right' => 'Relativity', 'xp' => 6, 'coins' => 3, 'marks' => 1],
                            ['left' => 'Darwin', 'right' => 'Evolution', 'xp' => 6, 'coins' => 3, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'Arrange the planets in order from the Sun',
                        'type' => 'arrange',
                        'xp' => 15,
                        'coins' => 7,
                        'marks' => 2,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Mercury', 'order' => 1],
                            ['text' => 'Venus', 'order' => 2],
                            ['text' => 'Earth', 'order' => 3],
                            ['text' => 'Mars', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // EXAM 3: English Grammar
            [
                'id' => 3,
                'name' => 'English Grammar',
                'type' => 'exam',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'Which sentence is grammatically correct?',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'She has been working here for five years.', 'is_correct' => true],
                            ['text' => 'She have been working here for five years.', 'is_correct' => false],
                            ['text' => 'She has working here for five years.', 'is_correct' => false],
                            ['text' => 'She been working here for five years.', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'The past tense of "go" is "went".',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'Match the words with their synonyms',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'en',
                        'pairs' => [
                            ['left' => 'Happy', 'right' => 'Joyful', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'Angry', 'right' => 'Furious', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'Big', 'right' => 'Large', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'Arrange the words to form a correct sentence',
                        'type' => 'arrange',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'I', 'order' => 1],
                            ['text' => 'love', 'order' => 2],
                            ['text' => 'learning', 'order' => 3],
                            ['text' => 'English', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // TRAINING 1: Algebra Practice
            [
                'id' => 4,
                'name' => 'Algebra Practice',
                'type' => 'training',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'Simplify: 3x + 2x',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => '5x', 'is_correct' => true],
                            ['text' => '6x', 'is_correct' => false],
                            ['text' => '5x²', 'is_correct' => false],
                            ['text' => '3x²', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'The equation x + 5 = 10 has the solution x = 5.',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'Match the algebraic expressions with their simplified forms',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'en',
                        'pairs' => [
                            ['left' => '2x + 3x', 'right' => '5x', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => '4x - x', 'right' => '3x', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'Arrange the steps to solve: 2x + 4 = 10',
                        'type' => 'arrange',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Subtract 4 from both sides', 'order' => 1],
                            ['text' => 'Get 2x = 6', 'order' => 2],
                            ['text' => 'Divide both sides by 2', 'order' => 3],
                            ['text' => 'Solution: x = 3', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // TRAINING 2: Physics Fundamentals
            [
                'id' => 5,
                'name' => 'Physics Fundamentals',
                'type' => 'training',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'What is the SI unit of force?',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Newton', 'is_correct' => true],
                            ['text' => 'Joule', 'is_correct' => false],
                            ['text' => 'Watt', 'is_correct' => false],
                            ['text' => 'Pascal', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'Energy can be created or destroyed.',
                        'type' => 'true_false',
                        'xp' => 6,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'Match the physical quantities with their units',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'en',
                        'pairs' => [
                            ['left' => 'Velocity', 'right' => 'm/s', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'Acceleration', 'right' => 'm/s²', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'Arrange the states of matter by increasing particle movement',
                        'type' => 'arrange',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Solid', 'order' => 1],
                            ['text' => 'Liquid', 'order' => 2],
                            ['text' => 'Gas', 'order' => 3],
                        ]
                    ],
                ]
            ],

            // TRAINING 3: English Vocabulary
            [
                'id' => 6,
                'name' => 'English Vocabulary',
                'type' => 'training',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'What is the meaning of "benevolent"?',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Kind and generous', 'is_correct' => true],
                            ['text' => 'Cruel and harsh', 'is_correct' => false],
                            ['text' => 'Lazy and unmotivated', 'is_correct' => false],
                            ['text' => 'Angry and upset', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'An antonym is a word that has the opposite meaning of another word.',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'Match the words with their antonyms',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'en',
                        'pairs' => [
                            ['left' => 'Hot', 'right' => 'Cold', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'Fast', 'right' => 'Slow', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'Arrange the words alphabetically',
                        'type' => 'arrange',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Apple', 'order' => 1],
                            ['text' => 'Banana', 'order' => 2],
                            ['text' => 'Cherry', 'order' => 3],
                            ['text' => 'Date', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // EXAM 4: الرياضيات - الفصل الدراسي الأول
            [
                'id' => 7,
                'name' => 'الرياضيات - الفصل الدراسي الأول',
                'type' => 'exam',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'ما قيمة س في المعادلة 3س + 7 = 22؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'ar',
                        'options' => [
                            ['text' => '5', 'is_correct' => true],
                            ['text' => '6', 'is_correct' => false],
                            ['text' => '4', 'is_correct' => false],
                            ['text' => '7', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'مجموع زوايا المثلث يساوي 180 درجة.',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'اربط الأشكال الهندسية بخصائصها',
                        'type' => 'connect',
                        'xp' => 15,
                        'coins' => 8,
                        'marks' => 3,
                        'language' => 'ar',
                        'pairs' => [
                            ['left' => 'المربع', 'right' => '4 أضلاع متساوية', 'xp' => 5, 'coins' => 3, 'marks' => 1],
                            ['left' => 'الدائرة', 'right' => 'لا توجد زوايا', 'xp' => 5, 'coins' => 3, 'marks' => 1],
                            ['left' => 'المثلث', 'right' => '3 أضلاع', 'xp' => 5, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'رتب الأرقام التالية ترتيباً تصاعدياً',
                        'type' => 'arrange',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'ar',
                        'options' => [
                            ['text' => '2', 'order' => 1],
                            ['text' => '5', 'order' => 2],
                            ['text' => '8', 'order' => 3],
                            ['text' => '12', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // EXAM 5: العلوم - الفصل الدراسي الأول
            [
                'id' => 8,
                'name' => 'العلوم - الفصل الدراسي الأول',
                'type' => 'exam',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'ما هو الرمز الكيميائي للماء؟',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'H2O', 'is_correct' => true],
                            ['text' => 'CO2', 'is_correct' => false],
                            ['text' => 'O2', 'is_correct' => false],
                            ['text' => 'H2', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'سرعة الضوء أسرع من سرعة الصوت.',
                        'type' => 'true_false',
                        'xp' => 6,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'اربط العلماء باكتشافاتهم',
                        'type' => 'connect',
                        'xp' => 18,
                        'coins' => 9,
                        'marks' => 3,
                        'language' => 'ar',
                        'pairs' => [
                            ['left' => 'نيوتن', 'right' => 'الجاذبية', 'xp' => 6, 'coins' => 3, 'marks' => 1],
                            ['left' => 'أينشتاين', 'right' => 'النسبية', 'xp' => 6, 'coins' => 3, 'marks' => 1],
                            ['left' => 'داروين', 'right' => 'التطور', 'xp' => 6, 'coins' => 3, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'رتب الكواكب حسب بعدها عن الشمس',
                        'type' => 'arrange',
                        'xp' => 15,
                        'coins' => 7,
                        'marks' => 2,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'عطارد', 'order' => 1],
                            ['text' => 'الزهرة', 'order' => 2],
                            ['text' => 'الأرض', 'order' => 3],
                            ['text' => 'المريخ', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // EXAM 6: اللغة العربية - النحو والإملاء
            [
                'id' => 9,
                'name' => 'اللغة العربية - النحو والإملاء',
                'type' => 'exam',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'أي جملة من الجمل التالية صحيحة نحوياً؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الطلاب يدرسون بجد', 'is_correct' => true],
                            ['text' => 'الطلاب يدرس بجد', 'is_correct' => false],
                            ['text' => 'الطلاب درس بجد', 'is_correct' => false],
                            ['text' => 'الطلاب درست بجد', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'الفعل الماضي من "يذهب" هو "ذهب".',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'اربط الكلمات بمرادفاتها',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'ar',
                        'pairs' => [
                            ['left' => 'سعيد', 'right' => 'فرحان', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'غاضب', 'right' => 'غضبان', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'كبير', 'right' => 'ضخم', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'رتب الكلمات لتكوين جملة صحيحة',
                        'type' => 'arrange',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'أحب', 'order' => 1],
                            ['text' => 'التعلم', 'order' => 2],
                            ['text' => 'اللغة', 'order' => 3],
                            ['text' => 'العربية', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // TRAINING 4: تدريب الجبر
            [
                'id' => 10,
                'name' => 'تدريب الجبر',
                'type' => 'training',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'بسط: 4س + 3س',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => '7س', 'is_correct' => true],
                            ['text' => '12س', 'is_correct' => false],
                            ['text' => '7س²', 'is_correct' => false],
                            ['text' => '4س²', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'المعادلة س + 3 = 8 لها الحل س = 5.',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'اربط التعبيرات الجبرية بأشكالها المبسطة',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'ar',
                        'pairs' => [
                            ['left' => '3س + 4س', 'right' => '7س', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => '5س - 2س', 'right' => '3س', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'رتب خطوات حل المعادلة: 3س + 6 = 15',
                        'type' => 'arrange',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'اطرح 6 من الطرفين', 'order' => 1],
                            ['text' => 'احصل على 3س = 9', 'order' => 2],
                            ['text' => 'اقسم الطرفين على 3', 'order' => 3],
                            ['text' => 'الحل: س = 3', 'order' => 4],
                        ]
                    ],
                ]
            ],

            // TRAINING 5: تدريب الفيزياء الأساسية
            [
                'id' => 11,
                'name' => 'تدريب الفيزياء الأساسية',
                'type' => 'training',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'ما هي وحدة القوة في النظام الدولي؟',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'نيوتن', 'is_correct' => true],
                            ['text' => 'جول', 'is_correct' => false],
                            ['text' => 'واط', 'is_correct' => false],
                            ['text' => 'باسكال', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'يمكن إنشاء أو تدمير الطاقة.',
                        'type' => 'true_false',
                        'xp' => 6,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'اربط الكميات الفيزيائية بوحداتها',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'ar',
                        'pairs' => [
                            ['left' => 'السرعة', 'right' => 'م/ث', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'التسارع', 'right' => 'م/ث²', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'رتب حالات المادة حسب زيادة حركة الجسيمات',
                        'type' => 'arrange',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 2,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'صلبة', 'order' => 1],
                            ['text' => 'سائلة', 'order' => 2],
                            ['text' => 'غازية', 'order' => 3],
                        ]
                    ],
                ]
            ],

            // TRAINING 6: تدريب مفردات اللغة العربية
            [
                'id' => 12,
                'name' => 'تدريب مفردات اللغة العربية',
                'type' => 'training',
                'question_count' => 4,
                'questions' => [
                    [
                        'title' => 'ما معنى كلمة "كريم"؟',
                        'type' => 'choice',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'سخي ومحسن', 'is_correct' => true],
                            ['text' => 'بخيل وقاسي', 'is_correct' => false],
                            ['text' => 'كسول وغير متحمس', 'is_correct' => false],
                            ['text' => 'غاضب ومستاء', 'is_correct' => false],
                        ]
                    ],
                    [
                        'title' => 'الضد هو كلمة لها معنى معاكس لكلمة أخرى.',
                        'type' => 'true_false',
                        'xp' => 5,
                        'coins' => 3,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'اربط الكلمات بأضدادها',
                        'type' => 'connect',
                        'xp' => 12,
                        'coins' => 6,
                        'marks' => 2,
                        'language' => 'ar',
                        'pairs' => [
                            ['left' => 'حار', 'right' => 'بارد', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                            ['left' => 'سريع', 'right' => 'بطيء', 'xp' => 4, 'coins' => 2, 'marks' => 1],
                        ]
                    ],
                    [
                        'title' => 'رتب الكلمات ترتيباً أبجدياً',
                        'type' => 'arrange',
                        'xp' => 8,
                        'coins' => 4,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'أحمد', 'order' => 1],
                            ['text' => 'بسام', 'order' => 2],
                            ['text' => 'تامر', 'order' => 3],
                            ['text' => 'جمال', 'order' => 4],
                        ]
                    ],
                ]
            ],
        ];
    }

    /**
     * Create questions for a specific exam/training
     */
    private function createExamTrainingQuestions(array $examTraining): void
    {
        $this->command->info("📝 Creating questions for: {$examTraining['name']} ({$examTraining['type']})");

        foreach ($examTraining['questions'] as $questionData) {
            $this->createQuestion($examTraining['id'], $questionData);
        }
    }

    /**
     * Create a single question with its options
     */
    private function createQuestion(int $examTrainingId, array $questionData): void
    {
        // Create the question
        $question = Question::create([
            'exam_training_id' => $examTrainingId,
            'title' => $questionData['title'],
            'type' => $questionData['type'],
            'language' => $questionData['language'] ?? 'en',
            'xp' => $questionData['xp'],
            'coins' => $questionData['coins'],
            'marks' => $questionData['marks'],
        ]);

        // Handle different question types
        switch ($questionData['type']) {
            case 'choice':
                $this->createChoiceOptions($question->id, $questionData['options']);
                break;

            case 'true_false':
                // True/False questions don't need additional options
                break;

            case 'connect':
                $this->createConnectPairs($question->id, $questionData['pairs']);
                break;

            case 'arrange':
                $this->createArrangeOptions($question->id, $questionData['options']);
                break;

            case 'written':
                // Written questions don't need additional options
                break;
        }
    }

    /**
     * Create choice question options
     */
    private function createChoiceOptions(int $questionId, array $options): void
    {
        foreach ($options as $option) {
            QuestionOption::create([
                'question_id' => $questionId,
                'text' => $option['text'],
                'is_correct' => $option['is_correct'],
            ]);
        }
    }

    /**
     * Create connect question pairs
     */
    private function createConnectPairs(int $questionId, array $pairs): void
    {
        foreach ($pairs as $pair) {
            $leftOption = QuestionOption::create([
                'question_id' => $questionId,
                'text' => $pair['left'],
                'side' => 'left',
            ]);

            $rightOption = QuestionOption::create([
                'question_id' => $questionId,
                'text' => $pair['right'],
                'side' => 'right',
            ]);

            QuestionOptionPair::create([
                'left_option_id' => $leftOption->id,
                'right_option_id' => $rightOption->id,
                'xp' => $pair['xp'],
                'coins' => $pair['coins'],
                'marks' => $pair['marks'],
            ]);
        }
    }

    /**
     * Create arrange question options
     */
    private function createArrangeOptions(int $questionId, array $options): void
    {
        foreach ($options as $option) {
            QuestionOption::create([
                'question_id' => $questionId,
                'text' => $option['text'],
                'arrange_order' => $option['order'],
            ]);
        }
    }
}
