<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\QuestionOptionPair;
use App\Infrastructure\Models\ExamTraining;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Questions Seeding...');

        $trainingsData = $this->getBookTrainingsQuestionsData();

        foreach ($trainingsData as $trainingData) {
            $this->createTrainingQuestions($trainingData);
        }

        $this->command->info('✅ Questions seeded successfully!');
    }

    /**
     * Get all book trainings questions data configuration
     * 
     * To add questions for a new book training, add the training title_ar to getBookTrainingQuestions()
     */
    private function getBookTrainingsQuestionsData(): array
    {
        return [
            $this->getBookTrainingQuestions('تدريب كتاب سناء في الفضاء'),
            $this->getBookTrainingQuestions('تدريب كتاب آدم يتخيل النحلة'),
            $this->getBookTrainingQuestions('تدريب كتاب عندما فقدت قطتي عقلها'),
            $this->getBookTrainingQuestions('تدريب كتاب ماذا رأى زيزو'),
            $this->getBookTrainingQuestions('تدريب كتاب لماذا انا مربع'),
        ];
    }

    /**
     * Get questions for a specific book training
     * 
     * To add a new book training's questions, create a new method like getBookTrainingQuestions()
     * and return the questions array with the training title_ar
     */
    private function getBookTrainingQuestions(string $trainingTitleAr): array
    {
        $questions = match ($trainingTitleAr) {
            'تدريب كتاب سناء في الفضاء' => $this->getSanaaInSpaceQuestions(),
            'تدريب كتاب آدم يتخيل النحلة' => $this->getAdamImaginesBeeQuestions(),
            'تدريب كتاب عندما فقدت قطتي عقلها' => $this->getWhenMyCatLostHerMindQuestions(),
            'تدريب كتاب ماذا رأى زيزو' => $this->getWhatDidZezoSeeQuestions(),
            'تدريب كتاب لماذا انا مربع' => $this->getWhyAmISquareQuestions(),
            default => [],
        };

        return [
            'training_title_ar' => $trainingTitleAr,
            'questions' => $questions,
        ];
    }

    /**
     * Questions for book: سناء في الفضاء
     */
    private function getSanaaInSpaceQuestions(): array
    {
        return [
            // تم إعادة ترتيب الأسئلة (12 سؤال: 6 choice + 3 arrange + 3 true_false)
            // اختر الإجابة الصحيحة
            [
                'title' => 'كانت سناء تلميذة في الصف __________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الرابع', 'is_correct' => false],
                    ['text' => 'الخامس', 'is_correct' => true],
                    ['text' => 'السادس', 'is_correct' => false],
                    ['text' => 'الثالث', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'كانت سناء تكره كتب الفلك لأنها مملة.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false, // خطأ
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ الأهل',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الأهل', 'order' => 1],
                    ['text' => 'احتفلوا', 'order' => 2],
                    ['text' => 'بعودة', 'order' => 3],
                    ['text' => 'سناء', 'order' => 4],
                    ['text' => 'من', 'order' => 5],
                    ['text' => 'رحلتها', 'order' => 6],
                    ['text' => 'الفضائية', 'order' => 7],
                    ['text' => 'الطويلة', 'order' => 8],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'أهدى خالها لها __________ لتراقب به الكواكب.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'مجهرًا', 'is_correct' => false],
                    ['text' => 'كتابًا', 'is_correct' => false],
                    ['text' => 'تلسكوبًا', 'is_correct' => true],
                    ['text' => 'هاتفًا', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'أهدى الخال راغب لابنة أخته سناء تلسكوبًا لمراقبة الكواكب.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'سافرت سناء مع خالها إلى كوكب __________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'المريخ', 'is_correct' => false],
                    ['text' => 'كيبْلَر', 'is_correct' => true],
                    ['text' => 'الزهرة', 'is_correct' => false],
                    ['text' => 'عطارد', 'is_correct' => false],
                ],
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ المركبة',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'المركبة', 'order' => 1],
                    ['text' => 'الفضائية', 'order' => 2],
                    ['text' => 'أقلعت', 'order' => 3],
                    ['text' => 'من', 'order' => 4],
                    ['text' => 'فناء', 'order' => 5],
                    ['text' => 'المنزل', 'order' => 6],
                    ['text' => 'بسرعة', 'order' => 7],
                    ['text' => 'كبيرة', 'order' => 8],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'قال خالها إنّ رحلتهما كانت أسرع من __________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الريح', 'is_correct' => false],
                    ['text' => 'الصوت', 'is_correct' => false],
                    ['text' => 'الضوء', 'is_correct' => true],
                    ['text' => 'الماء', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'صنع خال سناء مركبة فضائية تسير بسرعة الضوء.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ سناء',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'سناء', 'order' => 1],
                    ['text' => 'تقرأ', 'order' => 2],
                    ['text' => 'كتب', 'order' => 3],
                    ['text' => 'الفلك', 'order' => 4],
                    ['text' => 'بانبهار', 'order' => 5],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'الكلمة المخالفة في مجموعة الكلمات التالية هي: (كوكب – نجم – قمر – زهرة)',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'كوكب', 'is_correct' => false],
                    ['text' => 'نجم', 'is_correct' => false],
                    ['text' => 'قمر', 'is_correct' => false],
                    ['text' => 'زهرة', 'is_correct' => true],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'الكلمة المخالفة في مجموعة الكلمات التالية هي: (كيبْلَر – المريخ – الزهرة – الرياض)',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'كيبْلَر', 'is_correct' => false],
                    ['text' => 'المريخ', 'is_correct' => false],
                    ['text' => 'الزهرة', 'is_correct' => false],
                    ['text' => 'الرياض', 'is_correct' => true],
                ],
            ],
        ];
    }

    /**
     * Questions for book: آدم يتخيل النحلة
     */
    private function getAdamImaginesBeeQuestions(): array
    {
        return [
            // تم إعادة ترتيب الأسئلة واختيار 12 سؤال (6 choice + 3 arrange + 3 true_false)
            // اختر الإجابة الصحيحة
            [
                'title' => 'جذبَتْ آدم رائحةُ __________ المتفتحة بجانب منزله.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الأشجار', 'is_correct' => false],
                    ['text' => 'الزهور', 'is_correct' => true],
                    ['text' => 'الفواكه', 'is_correct' => false],
                    ['text' => 'الأعشاب', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'تخيّل آدم في البداية أن النحلة حشرة سوداء مخيفة.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ آدم',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'آدم', 'order' => 1],
                    ['text' => 'قرب', 'order' => 2],
                    ['text' => 'من', 'order' => 3],
                    ['text' => 'الأزهار', 'order' => 4],
                    ['text' => 'ليتأمل', 'order' => 5],
                    ['text' => 'ألوانها', 'order' => 6],
                    ['text' => 'الجميلة', 'order' => 7],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'قال الأخ إن النحلة تشبه __________ لكنها تلسع.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الفراشة', 'is_correct' => false],
                    ['text' => 'الذبابة', 'is_correct' => true],
                    ['text' => 'النملة', 'is_correct' => false],
                    ['text' => 'العصفور', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'رسم آدم في النهاية نحلة تجمع الصفات التي عرفها من الجميع.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'أوضح الأب أن النحلة حشرة __________ تصنع لنا العسل.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'مؤذية', 'is_correct' => false],
                    ['text' => 'مفيدة', 'is_correct' => true],
                    ['text' => 'صغيرة جدًا', 'is_correct' => false],
                    ['text' => 'خطير', 'is_correct' => false],
                ],
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ الأم',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الأم', 'order' => 1],
                    ['text' => 'أحضرت', 'order' => 2],
                    ['text' => 'كتابًا', 'order' => 3],
                    ['text' => 'فيه', 'order' => 4],
                    ['text' => 'صور', 'order' => 5],
                    ['text' => 'ملونة', 'order' => 6],
                    ['text' => 'عن', 'order' => 7],
                    ['text' => 'الحشرات', 'order' => 8],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'أهدت الجدة آدم جوارب __________ منقطة بالأسود.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'خضراء', 'is_correct' => false],
                    ['text' => 'صفراء', 'is_correct' => true],
                    ['text' => 'بيضاء', 'is_correct' => false],
                    ['text' => 'زرقاء', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'أخبره أخوه أن النحلة طائر جميل له ريش.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false, // خطأ
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ الأب',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الأب', 'order' => 1],
                    ['text' => 'لابنه', 'order' => 2],
                    ['text' => 'شرح', 'order' => 3],
                    ['text' => 'كيف', 'order' => 4],
                    ['text' => 'يصنع', 'order' => 5],
                    ['text' => 'النحل', 'order' => 6],
                    ['text' => 'العسل', 'order' => 7],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'من حذّر آدم من وجود نحلة داخل الزهرة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'والده', 'is_correct' => false],
                    ['text' => 'أخوه', 'is_correct' => true],
                    ['text' => 'جدته', 'is_correct' => false],
                    ['text' => 'أمه', 'is_correct' => false],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'ما الذي أحضرته الجدة لآدم؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'قميصًا أزرق', 'is_correct' => false],
                    ['text' => 'جوارب صفراء منقطة بالأسود', 'is_correct' => true],
                    ['text' => 'كتابًا عن النحل', 'is_correct' => false],
                    ['text' => 'قبعة ملونة', 'is_correct' => false],
                ],
            ],
            // ========== تم تعليق الأسئلة الزائدة (2 أسئلة) ==========
            // [
            //     'title' => 'ماذا جذب آدم في بداية القصة؟',
            //     'type' => 'choice',
            //     'xp' => 2,
            //     'coins' => 1,
            //     'marks' => 1,
            //     'language' => 'ar',
            //     'options' => [
            //         ['text' => 'صوت العصافير', 'is_correct' => false],
            //         ['text' => 'رائحة الزهور', 'is_correct' => true],
            //         ['text' => 'لون الفراشات', 'is_correct' => false],
            //         ['text' => 'طنين النحل', 'is_correct' => false],
            //     ],
            // ],
            // [
            //     'title' => 'ماذا فعلت الأم في نهاية القصة؟',
            //     'type' => 'choice',
            //     'xp' => 2,
            //     'coins' => 1,
            //     'marks' => 1,
            //     'language' => 'ar',
            //     'options' => [
            //         ['text' => 'قدمت العسل لآدم', 'is_correct' => false],
            //         ['text' => 'عرضت عليه صورة النحلة في كتاب', 'is_correct' => true],
            //         ['text' => 'ذهبت لتشتري العسل', 'is_correct' => false],
            //         ['text' => 'طلبت منه نسيان النحل', 'is_correct' => false],
            //     ],
            // ],
        ];
    }

    /**
     * Questions for book: عندما فقدت قطتي عقلها
     */
    private function getWhenMyCatLostHerMindQuestions(): array
    {
        return [
            // تم إعادة ترتيب الأسئلة واختيار 12 سؤال (7 choice + 2 arrange + 3 true_false)
            // اختر الإجابة الصحيحة
            [
                'title' => 'من هو بطل القصة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'سامي', 'is_correct' => false],
                    ['text' => 'رامي', 'is_correct' => true],
                    ['text' => 'خالد', 'is_correct' => false],
                    ['text' => 'فادي', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'عاد رامي إلى المنزل مسرورًا لأن يومه في المدرسة كان سهلًا.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false, // خطأ
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ رامي',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'رامي', 'order' => 1],
                    ['text' => 'بحث', 'order' => 2],
                    ['text' => 'عن', 'order' => 3],
                    ['text' => 'قطته', 'order' => 4],
                    ['text' => 'في', 'order' => 5],
                    ['text' => 'المنزل', 'order' => 6],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'ما الشيء الذي كان داخل الصندوق مع القطة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'زجاجة سُم', 'is_correct' => true],
                    ['text' => 'طعام', 'is_correct' => false],
                    ['text' => 'ماء', 'is_correct' => false],
                    ['text' => 'وسادة', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'لم يجد رامي قطته مشمشَة عندما عاد إلى المنزل.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'ما الذي يرمز إليه الصندوق في القصة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'بيت القطة', 'is_correct' => false],
                    ['text' => 'تجربة علمية', 'is_correct' => true],
                    ['text' => 'لعبة للأطفال', 'is_correct' => false],
                    ['text' => 'مكان للاختباء', 'is_correct' => false],
                ],
            ],
            // رتب الكلمات
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بــ عاد',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'عاد', 'order' => 1],
                    ['text' => 'رامي', 'order' => 2],
                    ['text' => 'إلى', 'order' => 3],
                    ['text' => 'المنزل', 'order' => 4],
                    ['text' => 'مسرورًا', 'order' => 5],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'كيف انتهت القصة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'اختفت القطة نهائيًا', 'is_correct' => false],
                    ['text' => 'تحولت إلى روبوت', 'is_correct' => false],
                    ['text' => 'عادت كما كانت', 'is_correct' => true],
                    ['text' => 'بقيت غاضبة من رامي', 'is_correct' => false],
                ],
            ],
            // صح أو خطأ
            [
                'title' => 'انتهت القصة بعودة مشمشَة إلى طبيعتها ولعبها مع رامي.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'عاد رامي إلى المنزل وهو يشعر بالتعب من يومٍ __________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'قصيرٍ', 'is_correct' => false],
                    ['text' => 'طويلٍ', 'is_correct' => true],
                    ['text' => 'سهلٍ', 'is_correct' => false],
                    ['text' => 'غريبٍ', 'is_correct' => false],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'لم يجد رامي قطته __________ في أي مكان.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'مشمشَة', 'is_correct' => true],
                    ['text' => 'مشمسة', 'is_correct' => false],
                    ['text' => 'مشربة', 'is_correct' => false],
                    ['text' => 'مشهورة', 'is_correct' => false],
                ],
            ],
            // اختر الإجابة الصحيحة
            [
                'title' => 'دخلت القطة __________ وأغلقته على نفسها.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'السرير', 'is_correct' => false],
                    ['text' => 'الصندوق', 'is_correct' => true],
                    ['text' => 'الدولاب', 'is_correct' => false],
                    ['text' => 'الكرسي', 'is_correct' => false],
                ],
            ],
            // ========== تم تعليق الأسئلة الزائدة (1 سؤال) ==========
            // [
            //     'title' => 'قالت القطة إنها ليست مشمشَة، بل قطة __________.',
            //     'type' => 'choice',
            //     'xp' => 2,
            //     'coins' => 1,
            //     'marks' => 1,
            //     'language' => 'ar',
            //     'options' => [
            //         ['text' => 'شرودنجر', 'is_correct' => true],
            //         ['text' => 'شرودر', 'is_correct' => false],
            //         ['text' => 'شرونجر', 'is_correct' => false],
            //         ['text' => 'شرودينجر', 'is_correct' => false],
            //     ],
            // ],
        ];
    }

    /**
     * Questions for book: لماذا انا مربع
     */
    private function getWhyAmISquareQuestions(): array
    {
        return [
            // Choice Questions
            [
                'title' => 'لماذا كان المربّع مختلفًا عن الآخرين؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'لأنه كان صغيرًا في العمر.', 'is_correct' => false],
                    ['text' => 'لأن شكله كان مربعًا في عالم من الدوائر.', 'is_correct' => true],
                    ['text' => 'لأنه لم يذهب إلى المدرسة.', 'is_correct' => false],
                    ['text' => 'لأنه يعيش في مدينة أخرى.', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'كيف ساعد المربّع زملاءه أثناء الزلزال؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'جرى ليطلب المساعدة من الشرطة.', 'is_correct' => false],
                    ['text' => 'اختبأ بعيدًا عنهم خوفًا.', 'is_correct' => false],
                    ['text' => 'وقف بثبات وسدّ الطريق إلى الهاوية.', 'is_correct' => true],
                    ['text' => 'صعد إلى أعلى الجبل بمفرده.', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ماذا تعلّم الجميع في نهاية القصة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'أن الشكل لا يهمّ في الصداقة.', 'is_correct' => false],
                    ['text' => 'أن لكلٍّ منا ما يميّزه ويجعله مميزًا.', 'is_correct' => true],
                    ['text' => 'أن المربّع أقوى من الدائرة.', 'is_correct' => false],
                    ['text' => 'أن الدوائر لا يمكنها التدحرج.', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'من ساعد ماما أثناء الولادة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الطبيب المربّع.', 'is_correct' => false],
                    ['text' => 'الطبيب الدائري والممرضات الدائريات.', 'is_correct' => true],
                    ['text' => 'الجدة المثلثة.', 'is_correct' => false],
                    ['text' => 'صديقه المربع.', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'كيف كان ردّ فعل الدوائر بعد إنقاذهم؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'تجاهلوه تمامًا وكأن شيئًا لم يحدث.', 'is_correct' => false],
                    ['text' => 'حملوه على الأكتاف وهتفوا له.', 'is_correct' => true],
                    ['text' => 'ابتعدوا عنه خوفًا.', 'is_correct' => false],
                    ['text' => 'عادوا إلى المدرسة دون أن يشكروه.', 'is_correct' => false],
                ],
            ],

            // Arrange Questions
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ بابا',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'بابا', 'order' => 1],
                    ['text' => 'دائرة', 'order' => 2],
                    ['text' => 'تزوّج', 'order' => 3],
                    ['text' => 'من', 'order' => 4],
                    ['text' => 'ماما', 'order' => 5],
                    ['text' => 'وعاشا', 'order' => 6],
                    ['text' => 'في', 'order' => 7],
                    ['text' => 'البيت', 'order' => 8],
                    ['text' => 'الدائري', 'order' => 9],
                ],
            ],
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ ذهب',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'ذهب', 'order' => 1],
                    ['text' => 'المربّع', 'order' => 2],
                    ['text' => 'إلى', 'order' => 3],
                    ['text' => 'المدرسة', 'order' => 4],
                    ['text' => 'الدائرية', 'order' => 5],
                    ['text' => 'مع', 'order' => 6],
                    ['text' => 'أصدقائه', 'order' => 7],
                ],
            ],
            [
                'title' => 'رتب الكلمات التالية لتصبح جملة مفيدة تبدأ بـــــ ساعد',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'ساعد', 'order' => 1],
                    ['text' => 'المربّع', 'order' => 2],
                    ['text' => 'زملاءه', 'order' => 3],
                    ['text' => 'أثناء', 'order' => 4],
                    ['text' => 'الزلزال', 'order' => 5],
                    ['text' => 'الكبير', 'order' => 6],
                ],
            ],

            // True/False Questions
            [
                'title' => 'وُلد الطفل على شكل مربع في عالم كله دوائر.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            [
                'title' => 'استخدم المربّع شجاعته ليساعد زملاءه أثناء الزلزال.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            [
                'title' => 'كانت الدوائر في المدرسة تُشجّع المربّع دائمًا وتُصفّق له.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false, // خطأ
            ],
        ];
    }

    /**
     * Questions for book: ماذا رأى زيزو
     */
    private function getWhatDidZezoSeeQuestions(): array
    {
        return [
            // True/False Questions
            [
                'title' => 'طائر زيزو كان يطير منخفضًا بالقرب من الأرض.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false, // خطأ
            ],
            [
                'title' => 'اصطدم زيزو بشيء ضخم أثناء طيرانه.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            [
                'title' => 'ظنّ زيزو أولًا أن الشيء الضخم هو عش كبير.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],
            [
                'title' => 'شعر زيزو ببرودة شديدة عندما اقترب من الشيء.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false, // خطأ
            ],
            [
                'title' => 'في النهاية عرف زيزو أن الشيء الضخم هو المنطاد.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true, // صح
            ],

            // Choice Questions
            [
                'title' => 'ماذا حدث لزيزو فجأة أثناء طيرانه؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'اصطاد دودة', 'is_correct' => false],
                    ['text' => 'اصطدم بشيء ضخم', 'is_correct' => true],
                    ['text' => 'نام على غصن', 'is_correct' => false],
                    ['text' => 'وقع في الماء', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ماذا ظنّ زيزو في البداية أن الشيء الضخم هو؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'جبل', 'is_correct' => false],
                    ['text' => 'شمس', 'is_correct' => false],
                    ['text' => 'عش ضخم', 'is_correct' => true],
                    ['text' => 'غيمة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ما الشيء الذي قفز عليه زيزو؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'حبل طويل', 'is_correct' => true],
                    ['text' => 'غصن شجرة', 'is_correct' => false],
                    ['text' => 'شبكة صياد', 'is_correct' => false],
                    ['text' => 'سلم خشبي', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ماذا كان ذلك الشيء الضخم في الحقيقة؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'دودة كبيرة', 'is_correct' => false],
                    ['text' => 'جبل مرتفع', 'is_correct' => false],
                    ['text' => 'بالون صغير', 'is_correct' => false],
                    ['text' => 'المنطاد', 'is_correct' => true],
                ],
            ],

            // Fill in the blank (Choice type)
            [
                'title' => 'شعر زيزو أثناء اقترابه من الشيء بــ ________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'برد شديد', 'is_correct' => false],
                    ['text' => 'حرارة شديدة', 'is_correct' => true],
                    ['text' => 'ريح قوية', 'is_correct' => false],
                    ['text' => 'خوف شديد', 'is_correct' => false],
                ],
            ],
        ];
    }

    /**
     * Create questions for a specific training
     */
    private function createTrainingQuestions(array $trainingData): void
    {
        $training = ExamTraining::where('title_ar', $trainingData['training_title_ar'])->first();

        if (!$training) {
            $this->command->warn("⚠️  Training not found: {$trainingData['training_title_ar']}");
            return;
        }

        $this->command->info("📝 Creating questions for: {$trainingData['training_title_ar']}");

        $questionCount = 0;
        foreach ($trainingData['questions'] as $questionData) {
            $this->createQuestion($training->id, $questionData);
            $questionCount++;
        }

        $this->command->info("   ✅ Created {$questionCount} questions");
    }

    /**
     * Create a single question with its options
     */
    private function createQuestion(int $examTrainingId, array $questionData): void
    {
        // Extract question metadata
        $type = $questionData['type'];
        $language = $questionData['language'] ?? 'ar';

        // Create the question
        $question = Question::create([
            'exam_training_id' => $examTrainingId,
            'title' => $questionData['title'],
            'type' => $type,
            'language' => $language,
            'xp' => $questionData['xp'],
            'coins' => $questionData['coins'],
            'marks' => $questionData['marks'],
        ]);

        // Handle different question types
        switch ($type) {
            case 'choice':
                $this->createChoiceOptions($question->id, $questionData['options']);
                break;

            case 'true_false':
                $this->createTrueFalseOption($question->id, $questionData['is_correct'] ?? true);
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
     * Create true/false question option
     */
    private function createTrueFalseOption(int $questionId, bool $isCorrect): void
    {
        QuestionOption::create([
            'question_id' => $questionId,
            'text' => 'صح',
            'is_correct' => $isCorrect,
        ]);
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
                'xp' => $pair['xp'] ?? 0,
                'coins' => $pair['coins'] ?? 0,
                'marks' => $pair['marks'] ?? 0,
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