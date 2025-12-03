<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Assignment;
use App\Infrastructure\Models\ExamTraining;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get teacher ID (assuming teacher ID 1 exists)
        $teacherId = 1;

        // Create assignments
        $assignmentsData = $this->getAssignmentsData();

        foreach ($assignmentsData as $assignmentData) {
            $this->createAssignmentWithTraining($assignmentData, $teacherId);
        }

        $this->command->info('✅ Assignments seeded successfully!');
        $this->command->info('📊 Total assignments created: ' . count($assignmentsData));
    }

    /**
     * Get all assignments data configuration
     */
    private function getAssignmentsData(): array
    {
        return [
            $this->getUnitOneTestAssignment(),
            $this->getUnitOneExamAssignment(),
            $this->getExamUnitOneAssignment(),
        ];
    }

    /**
     * Assignment 1: اختبار نهاية الوحدة الأولى (تمرين)
     */
    private function getUnitOneTestAssignment(): array
    {
        return [
            'title_ar' => 'اختبار نهاية الوحدة الأولى',
            'title_en' => 'Unit One End Test',
            'type' => 'training',
            'total_xp' => 40,
            'total_coins' => 20,
            'total_marks' => 40,
            'questions_count' => 20,
            'questions' => [
                // اختر الإجابة الصحيحة (8 أسئلة)
                [
                    'title' => 'تسمى مجموعة المكونات الحية وغير الحية المرتبطة بعلاقات متبادلة في منطقة معينة بـ:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'الجماعة', 'is_correct' => false],
                        ['text' => 'الفرد', 'is_correct' => false],
                        ['text' => 'النظام', 'is_correct' => false],
                        ['text' => 'البيئة', 'is_correct' => true],
                    ],
                ],
                [
                    'title' => 'تتكاثر نباتات مثل الخنشار باستخدام:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'البذور', 'is_correct' => false],
                        ['text' => 'المخاريط', 'is_correct' => false],
                        ['text' => 'الأوراق', 'is_correct' => false],
                        ['text' => 'الأبواغ', 'is_correct' => true],
                    ],
                ],
                [
                    'title' => 'المادة النقية المكونة من نوع واحد من الذرات فقط هي:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'المركب', 'is_correct' => false],
                        ['text' => 'المخلوط', 'is_correct' => false],
                        ['text' => 'العنصر', 'is_correct' => true],
                        ['text' => 'الركيزة', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'أحد العنصرين المكونين لمركب أكسيد الحديد (Fe₂O₃) هو:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'الكربون', 'is_correct' => false],
                        ['text' => 'الكبريت', 'is_correct' => false],
                        ['text' => 'الحديد', 'is_correct' => true],
                        ['text' => 'الصوديوم', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'الوقود الذي يسبب ارتفاع الحرارة في المدن الصناعية وتغير المناخ هو الوقود:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'النباتي', 'is_correct' => false],
                        ['text' => 'الكربوني', 'is_correct' => false],
                        ['text' => 'الأحفوري', 'is_correct' => true],
                        ['text' => 'النووي', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'العدسة المحدبة تستخدم في إشعال النار لأنها تقوم بـ ………… الضوء في نقطة واحدة:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'تشتيت', 'is_correct' => false],
                        ['text' => 'تفرقة', 'is_correct' => false],
                        ['text' => 'جمع', 'is_correct' => true],
                        ['text' => 'عكس', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'يتم سماع الصوت نتيجة اهتزاز:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'القوقعة', 'is_correct' => false],
                        ['text' => 'العظمة', 'is_correct' => false],
                        ['text' => 'طبلة الأذن', 'is_correct' => true],
                        ['text' => 'أذن', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'أي مما يلي يُعد من الكائنات الحية المنتجة؟',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'الفطر', 'is_correct' => false],
                        ['text' => 'الأسد', 'is_correct' => false],
                        ['text' => 'النبات', 'is_correct' => true],
                        ['text' => 'الإنسان', 'is_correct' => false],
                    ],
                ],
                // أكمل الفراغ (6 أسئلة)
                [
                    'title' => 'النباتات الخضراء تقوم بعملية تصنيع الغذاء في عملية تسمى ____________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'التخمّر', 'is_correct' => false],
                        ['text' => 'التنفس', 'is_correct' => false],
                        ['text' => 'البناء الضوئي', 'is_correct' => true],
                        ['text' => 'التكاثر', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'تنتقل الطاقة في السلسلة الغذائية من المنتجين إلى ____________ .',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'المحللات', 'is_correct' => false],
                        ['text' => 'البيئة', 'is_correct' => false],
                        ['text' => 'المستهلكين', 'is_correct' => true],
                        ['text' => 'الشمس', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'المادة التي لا يمكن تجزئتها إلى مواد أبسط تُسمى ____________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'المركب', 'is_correct' => false],
                        ['text' => 'الخليط', 'is_correct' => false],
                        ['text' => 'المحلول', 'is_correct' => false],
                        ['text' => 'العنصر', 'is_correct' => true],
                    ],
                ],
                [
                    'title' => 'تتحرك الموجات الصوتية في الهواء على شكل ____________ .',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'موجات ضوئية', 'is_correct' => false],
                        ['text' => 'حرارة', 'is_correct' => false],
                        ['text' => 'اهتزازات', 'is_correct' => true],
                        ['text' => 'انكسارات', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'يحدث الصدأ للحديد عندما يتفاعل مع الماء و___________ .',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'الهيدروجين', 'is_correct' => false],
                        ['text' => 'الكربون', 'is_correct' => false],
                        ['text' => 'النيتروجين', 'is_correct' => false],
                        ['text' => 'الأكسجين', 'is_correct' => true],
                    ],
                ],
                [
                    'title' => 'نوع من الطاقة ناتج عن حركة الأجسام يسمى طاقة ____________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'ضوئية', 'is_correct' => false],
                        ['text' => 'حرارية', 'is_correct' => false],
                        ['text' => 'كهربائية', 'is_correct' => false],
                        ['text' => 'حركية', 'is_correct' => true],
                    ],
                ],
                // صح أو خطأ (6 أسئلة)
                [
                    'title' => 'يعتبر النحل من المفصليات المفيدة للإنسان، لكن العقرب ليس كذلك.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                [
                    'title' => 'الماء هو مثال لمورد طبيعي غير متجدد لأنه يوجد بكميات محدودة.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                [
                    'title' => 'عند تكون المركب فإنه يحتفظ بصفات مكوناته الأصلية.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                [
                    'title' => 'الزلازل والبراكين تسبب تغيرات بطيئة جداً في تضاريس الأرض.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                [
                    'title' => 'قوس المطر ينتج عن انكسار الضوء داخل قطرات الماء.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                [
                    'title' => 'جميع الكائنات الحية التي تعيش في منطقة واحدة تشكل معاً جماعة حيوية.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
            ],
        ];
    }

    /**
     * Assignment 2: امتحان الوحدة الاولى (امتحان)
     */
    private function getUnitOneExamAssignment(): array
    {
        return [
            'title_ar' => 'امتحان الوحدة الاولى',
            'title_en' => 'Unit One Exam',
            'type' => 'exam',
            'total_xp' => 40,
            'total_coins' => 20,
            'total_marks' => 40,
            'questions_count' => 20,
            'questions' => [
                // صح أو خطأ (5 أسئلة)
                [
                    'title' => 'الجملة الاسمية تبدأ باسم.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                [
                    'title' => 'الفعل المضارع يدل على حدث وقع في الماضي.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                [
                    'title' => 'الضمير (هو) يعود على المفرد المذكر الغائب.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                [
                    'title' => 'الفاعل دائمًا يأتي بعد الفعل.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                [
                    'title' => 'المثنى يدل على شخصين أو شيئين.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // أكمل الفراغ (6 أسئلة)
                [
                    'title' => 'الفعل الماضي يدل على حدث ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'سيقع', 'is_correct' => false],
                        ['text' => 'يقع الآن', 'is_correct' => false],
                        ['text' => 'وقع سابقًا', 'is_correct' => true],
                        ['text' => 'لم يقع', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'الضمير (نحن) يدل على ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'المفرد المذكر', 'is_correct' => false],
                        ['text' => 'المفرد المؤنث', 'is_correct' => false],
                        ['text' => 'الجماعة المتكلمة', 'is_correct' => true],
                        ['text' => 'الغائب', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'جمع كلمة (ولد) هو ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'أولاد', 'is_correct' => true],
                        ['text' => 'ولدان', 'is_correct' => false],
                        ['text' => 'ولود', 'is_correct' => false],
                        ['text' => 'وليد', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'نوع كلمة (مسرور) هو ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'فعل', 'is_correct' => false],
                        ['text' => 'اسم فاعل', 'is_correct' => false],
                        ['text' => 'اسم مفعول', 'is_correct' => false],
                        ['text' => 'صفة', 'is_correct' => true],
                    ],
                ],
                [
                    'title' => 'الفاعل في الجملة هو من ________ الفعل.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'يقع عليه', 'is_correct' => false],
                        ['text' => 'يقوم به', 'is_correct' => true],
                        ['text' => 'ينتهي به', 'is_correct' => false],
                        ['text' => 'يصفه', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'المبتدأ هو ________ الجملة الاسمية.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'أول كلمة', 'is_correct' => true],
                        ['text' => 'آخر كلمة', 'is_correct' => false],
                        ['text' => 'الفعل', 'is_correct' => false],
                        ['text' => 'الضمير', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'جمع المؤنث السالم ينتهي غالبًا بـ ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'ان', 'is_correct' => false],
                        ['text' => 'ون', 'is_correct' => false],
                        ['text' => 'ات', 'is_correct' => true],
                        ['text' => 'ين', 'is_correct' => false],
                    ],
                ],
                // اختر الإجابة الصحيحة (9 أسئلة)
                [
                    'title' => 'الفعل المضارع في الجملة: (يكتبُ الطفلُ الدرسَ) هو:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'الطفل', 'is_correct' => false],
                        ['text' => 'الدرس', 'is_correct' => false],
                        ['text' => 'يكتبُ', 'is_correct' => true],
                        ['text' => 'كتاب', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'الكلمة التي تحتوي على همزة قطع هي:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'ابن', 'is_correct' => false],
                        ['text' => 'امرأة', 'is_correct' => true],
                        ['text' => 'استمع', 'is_correct' => false],
                        ['text' => 'اجلس', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'معنى كلمة (واسع):',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'ضيق', 'is_correct' => false],
                        ['text' => 'كبير المساحة', 'is_correct' => true],
                        ['text' => 'قصير', 'is_correct' => false],
                        ['text' => 'صغير', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'الفاعل في جملة: (سافرَ الرجلُ).',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'سافرَ', 'is_correct' => false],
                        ['text' => 'الرجلُ', 'is_correct' => true],
                        ['text' => 'السفر', 'is_correct' => false],
                        ['text' => 'هو', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'الكلمة التي تعد اسم مفعول هي:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'مكتوب', 'is_correct' => true],
                        ['text' => 'كاتب', 'is_correct' => false],
                        ['text' => 'لاعب', 'is_correct' => false],
                        ['text' => 'نائم', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'أي الجمل التالية جملة فعلية؟',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'الولدُ سعيدٌ', 'is_correct' => false],
                        ['text' => 'الكتابُ مفيدٌ', 'is_correct' => false],
                        ['text' => 'يلعبُ الطفلُ', 'is_correct' => true],
                        ['text' => 'الشجرةُ طويلةٌ', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'مرادف كلمة (سريع):',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'بطيء', 'is_correct' => false],
                        ['text' => 'قوي', 'is_correct' => false],
                        ['text' => 'نشيط', 'is_correct' => false],
                        ['text' => 'حثيث', 'is_correct' => true],
                    ],
                ],
                [
                    'title' => 'ضد كلمة (قريب):',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'بعيد', 'is_correct' => true],
                        ['text' => 'آخر', 'is_correct' => false],
                        ['text' => 'ضيق', 'is_correct' => false],
                        ['text' => 'قليل', 'is_correct' => false],
                    ],
                ],
            ],
        ];
    }

    /**
     * Assignment 3: Exam Unit One (تمرين)
     */
    private function getExamUnitOneAssignment(): array
    {
        return [
            'title_ar' => 'Exam Unit One',
            'title_en' => 'Exam Unit One',
            'type' => 'training',
            'total_xp' => 50,
            'total_coins' => 25,
            'total_marks' => 50,
            'questions_count' => 25,
            'questions' => [
                // صح أو خطأ (5 أسئلة)
                [
                    'title' => 'The word "cat" is a noun.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'en',
                ],
                [
                    'title' => 'Adjectives describe nouns.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'en',
                ],
                [
                    'title' => 'The verb "run" is in the past tense.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'en',
                ],
                [
                    'title' => 'A sentence must start with a capital letter.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'en',
                ],
                [
                    'title' => 'The opposite of "big" is "tall."',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'en',
                ],
                // أكمل الفراغ (7 أسئلة)
                [
                    'title' => 'The plural of "child" is ________.',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'childs', 'is_correct' => false],
                        ['text' => 'children', 'is_correct' => true],
                        ['text' => 'childes', 'is_correct' => false],
                        ['text' => 'childen', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'She ________ to school every day.',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'go', 'is_correct' => false],
                        ['text' => 'goes', 'is_correct' => true],
                        ['text' => 'went', 'is_correct' => false],
                        ['text' => 'going', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'My favorite color is ________.',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'fast', 'is_correct' => false],
                        ['text' => 'blue', 'is_correct' => true],
                        ['text' => 'run', 'is_correct' => false],
                        ['text' => 'slowly', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'The opposite of "happy" is ________.',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Sad', 'is_correct' => true],
                        ['text' => 'Glad', 'is_correct' => false],
                        ['text' => 'Smile', 'is_correct' => false],
                        ['text' => 'kind', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'We ________ pizza yesterday.',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Eat', 'is_correct' => false],
                        ['text' => 'Eating', 'is_correct' => false],
                        ['text' => 'Ate', 'is_correct' => true],
                        ['text' => 'Eats', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'A ________ is a place where we read books.',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'School', 'is_correct' => false],
                        ['text' => 'Library', 'is_correct' => true],
                        ['text' => 'Hospital', 'is_correct' => false],
                        ['text' => 'Park', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'They are ________ soccer now.',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Play', 'is_correct' => false],
                        ['text' => 'Played', 'is_correct' => false],
                        ['text' => 'Plays', 'is_correct' => false],
                        ['text' => 'Playing', 'is_correct' => true],
                    ],
                ],
                // اختر الإجابة الصحيحة (8 أسئلة)
                [
                    'title' => 'Which word is an adjective?',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Quickly', 'is_correct' => false],
                        ['text' => 'Beautiful', 'is_correct' => true],
                        ['text' => 'Run', 'is_correct' => false],
                        ['text' => 'Boy', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'Which sentence is in the past tense?',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'I play football', 'is_correct' => false],
                        ['text' => 'I am playing football.', 'is_correct' => false],
                        ['text' => 'I played football', 'is_correct' => true],
                        ['text' => 'I will play football.', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'What is the correct plural of "box"?',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Boxs', 'is_correct' => false],
                        ['text' => 'Boxies', 'is_correct' => false],
                        ['text' => 'Boxes', 'is_correct' => true],
                        ['text' => 'Boxen', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => '"He is a smart boy." The word "smart" is a:',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Noun', 'is_correct' => false],
                        ['text' => 'Verb', 'is_correct' => false],
                        ['text' => 'Adjective', 'is_correct' => true],
                        ['text' => 'Adverb', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'Which word is a verb?',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Teacher', 'is_correct' => false],
                        ['text' => 'Dance', 'is_correct' => true],
                        ['text' => 'Table', 'is_correct' => false],
                        ['text' => 'Happy', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => '"The cat is under the table." The word "under" is a:',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Noun', 'is_correct' => false],
                        ['text' => 'Verb', 'is_correct' => false],
                        ['text' => 'Preposition', 'is_correct' => true],
                        ['text' => 'Adjective', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'Which of the following is a complete sentence?',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'The big', 'is_correct' => false],
                        ['text' => 'Running fast', 'is_correct' => false],
                        ['text' => 'The dog barked', 'is_correct' => true],
                        ['text' => 'The blue', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'What is the correct possessive form? "The bag of Sara"',
                    'type' => 'choice',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'Saras bag', 'is_correct' => false],
                        ['text' => 'Sara bag', 'is_correct' => false],
                        ['text' => 'Sara\'s bag', 'is_correct' => true],
                        ['text' => 'Saran bag', 'is_correct' => false],
                    ],
                ],
                // أعد ترتيب الكلمات (5 أسئلة)
                [
                    'title' => '( playing – is – she – tennis – blue )',
                    'type' => 'arrange',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'She', 'order' => 1],
                        ['text' => 'is', 'order' => 2],
                        ['text' => 'playing', 'order' => 3],
                        ['text' => 'tennis', 'order' => 4],
                    ],
                ],
                [
                    'title' => '( school – to – they – go – fast – the )',
                    'type' => 'arrange',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'They', 'order' => 1],
                        ['text' => 'go', 'order' => 2],
                        ['text' => 'to', 'order' => 3],
                        ['text' => 'school', 'order' => 4],
                    ],
                ],
                [
                    'title' => '( cat – the – sleeping – is – happy – very )',
                    'type' => 'arrange',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'The', 'order' => 1],
                        ['text' => 'cat', 'order' => 2],
                        ['text' => 'is', 'order' => 3],
                        ['text' => 'sleeping', 'order' => 4],
                    ],
                ],
                [
                    'title' => '( book – reading – I – am – blue – the )',
                    'type' => 'arrange',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'I', 'order' => 1],
                        ['text' => 'am', 'order' => 2],
                        ['text' => 'reading', 'order' => 3],
                        ['text' => 'the', 'order' => 4],
                        ['text' => 'book', 'order' => 5],
                    ],
                ],
                [
                    'title' => '( eats – every – breakfast – he – tall – day )',
                    'type' => 'arrange',
                    'language' => 'en',
                    'options' => [
                        ['text' => 'He', 'order' => 1],
                        ['text' => 'eats', 'order' => 2],
                        ['text' => 'breakfast', 'order' => 3],
                        ['text' => 'every', 'order' => 4],
                        ['text' => 'day', 'order' => 5],
                    ],
                ],
            ],
        ];
    }

    /**
     * Create assignment with training and questions
     */
    private function createAssignmentWithTraining(array $assignmentData, int $teacherId): void
    {
        // Calculate points per question
        $questionsCount = $assignmentData['questions_count'];
        $xpPerQuestion = (int) ($assignmentData['total_xp'] / $questionsCount);
        $coinsPerQuestion = (int) ($assignmentData['total_coins'] / $questionsCount);
        $marksPerQuestion = (int) ($assignmentData['total_marks'] / $questionsCount);

        // Create ExamTraining
        $training = ExamTraining::create([
            'title' => $assignmentData['title_en'],
            'title_ar' => $assignmentData['title_ar'],
            'description' => "Training for {$assignmentData['title_en']}",
            'description_ar' => "تدريب لـ {$assignmentData['title_ar']}",
            'type' => $assignmentData['type'],
            'duration' => $assignmentData['type'] === 'exam' ? 60 : null, // 60 minutes for exams
            'created_by' => $teacherId,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(2),
        ]);

        $this->command->info("📝 Created training: {$training->title_ar}");

        // Create questions
        $questionCount = 0;
        foreach ($assignmentData['questions'] as $questionData) {
            $this->createQuestion(
                $training->id,
                $questionData,
                $xpPerQuestion,
                $coinsPerQuestion,
                $marksPerQuestion
            );
            $questionCount++;
        }

        $this->command->info("   ✅ Created {$questionCount} questions");

        // Create Assignment
        $assignment = Assignment::create([
            'title_ar' => $assignmentData['title_ar'],
            'title_en' => $assignmentData['title_en'],
            'assignable_type' => 'examTraining',
            'assignable_id' => $training->id,
            'teacher_id' => $teacherId,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(14),
        ]);

        // Attach student ID 1 to the assignment
        $studentId = 1;
        DB::table('assignment_student')->insert([
            'assignment_id' => $assignment->id,
            'student_id' => $studentId,
            'status' => 'not_started',
            'assigned_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->command->info("   ✅ Created assignment: {$assignment->title_ar}");
        $this->command->info("   👤 Assigned to student ID: {$studentId}");
        $this->command->newLine();
    }

    /**
     * Create a single question with its options
     */
    private function createQuestion(
        int $examTrainingId,
        array $questionData,
        int $xp,
        int $coins,
        int $marks
    ): void {
        $type = $questionData['type'];
        $language = $questionData['language'] ?? 'ar';

        // Create the question
        $question = Question::create([
            'exam_training_id' => $examTrainingId,
            'title' => $questionData['title'],
            'type' => $type,
            'language' => $language,
            'xp' => $xp,
            'coins' => $coins,
            'marks' => $marks,
        ]);

        // Handle different question types
        switch ($type) {
            case 'choice':
                $this->createChoiceOptions($question->id, $questionData['options']);
                break;

            case 'true_false':
                $this->createTrueFalseOption($question->id, $questionData['is_correct'] ?? true, $language);
                break;

            case 'arrange':
                $this->createArrangeOptions($question->id, $questionData['options']);
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
    private function createTrueFalseOption(int $questionId, bool $isCorrect, string $language = 'en'): void
    {
        $text = $language === 'ar' ? 'صح' : 'True';

        QuestionOption::create([
            'question_id' => $questionId,
            'text' => $text,
            'is_correct' => $isCorrect,
        ]);
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
