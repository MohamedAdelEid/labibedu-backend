<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Assignment;
use App\Infrastructure\Models\ExamTraining;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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


        // Create assignment from book (آدم يتخيل النحلة) - New separate method
        $this->createAdamBeeBookAssignment($teacherId);

        $this->command->info('✅ Assignments seeded successfully!');
        $this->command->info('📊 Total assignments created: ' . (count($assignmentsData) + 2));
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

            $this->getActiveParticipleAssignment(),
        ];
    }

    /**
     * Assignment 1: اختبار نهاية الوحدة الأولى (امتحان)
     */
    private function getUnitOneTestAssignment(): array
    {
        return [
            'title_ar' => 'اختبار نهاية الوحدة الأولى',
            'title_en' => 'Unit One End Test',
            'type' => 'exam',
            'duration' => 30,
            'total_xp' => 40,
            'total_coins' => 20,
            'total_marks' => 40,
            'questions_count' => 12,
            'questions' => [
                // تم إعادة ترتيب الأسئلة واختيار 12 سؤال (7 choice + 5 true_false)
                // اختر الإجابة الصحيحة
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
                // صح أو خطأ
                [
                    'title' => 'يعتبر النحل من المفصليات المفيدة للإنسان، لكن العقرب ليس كذلك.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // اختر الإجابة الصحيحة
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
                // صح أو خطأ
                [
                    'title' => 'الماء هو مثال لمورد طبيعي غير متجدد لأنه يوجد بكميات محدودة.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                // أكمل الفراغ
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
                // صح أو خطأ
                [
                    'title' => 'قوس المطر ينتج عن انكسار الضوء داخل قطرات الماء.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // اختر الإجابة الصحيحة
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
                // صح أو خطأ
                [
                    'title' => 'جميع الكائنات الحية التي تعيش في منطقة واحدة تشكل معاً جماعة حيوية.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // أكمل الفراغ
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
                // صح أو خطأ
                [
                    'title' => 'الزلازل والبراكين تسبب تغيرات بطيئة جداً في تضاريس الأرض.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                // أكمل الفراغ
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
                // اختر الإجابة الصحيحة
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
                // ========== تم تعليق الأسئلة الزائدة (8 أسئلة) ==========
                // [
                //     'title' => 'تتكاثر نباتات مثل الخنشار باستخدام:',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'البذور', 'is_correct' => false],
                //         ['text' => 'المخاريط', 'is_correct' => false],
                //         ['text' => 'الأوراق', 'is_correct' => false],
                //         ['text' => 'الأبواغ', 'is_correct' => true],
                //     ],
                // ],
                // [
                //     'title' => 'أحد العنصرين المكونين لمركب أكسيد الحديد (Fe₂O₃) هو:',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'الكربون', 'is_correct' => false],
                //         ['text' => 'الكبريت', 'is_correct' => false],
                //         ['text' => 'الحديد', 'is_correct' => true],
                //         ['text' => 'الصوديوم', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'الوقود الذي يسبب ارتفاع الحرارة في المدن الصناعية وتغير المناخ هو الوقود:',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'النباتي', 'is_correct' => false],
                //         ['text' => 'الكربوني', 'is_correct' => false],
                //         ['text' => 'الأحفوري', 'is_correct' => true],
                //         ['text' => 'النووي', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'يتم سماع الصوت نتيجة اهتزاز:',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'القوقعة', 'is_correct' => false],
                //         ['text' => 'العظمة', 'is_correct' => false],
                //         ['text' => 'طبلة الأذن', 'is_correct' => true],
                //         ['text' => 'أذن', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'المادة التي لا يمكن تجزئتها إلى مواد أبسط تُسمى ____________.',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'المركب', 'is_correct' => false],
                //         ['text' => 'الخليط', 'is_correct' => false],
                //         ['text' => 'المحلول', 'is_correct' => false],
                //         ['text' => 'العنصر', 'is_correct' => true],
                //     ],
                // ],
                // [
                //     'title' => 'تتحرك الموجات الصوتية في الهواء على شكل ____________ .',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'موجات ضوئية', 'is_correct' => false],
                //         ['text' => 'حرارة', 'is_correct' => false],
                //         ['text' => 'اهتزازات', 'is_correct' => true],
                //         ['text' => 'انكسارات', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'نوع من الطاقة ناتج عن حركة الأجسام يسمى طاقة ____________.',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'ضوئية', 'is_correct' => false],
                //         ['text' => 'حرارية', 'is_correct' => false],
                //         ['text' => 'كهربائية', 'is_correct' => false],
                //         ['text' => 'حركية', 'is_correct' => true],
                //     ],
                // ],
                // [
                //     'title' => 'عند تكون المركب فإنه يحتفظ بصفات مكوناته الأصلية.',
                //     'type' => 'true_false',
                //     'is_correct' => false,
                //     'language' => 'ar',
                // ],
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
            'duration' => 30,
            'total_xp' => 40,
            'total_coins' => 20,
            'total_marks' => 40,
            'questions_count' => 12,
            'questions' => [
                // تم إعادة ترتيب الأسئلة واختيار 12 سؤال (4 true_false + 8 choice)
                // صح أو خطأ
                [
                    'title' => 'الجملة الاسمية تبدأ باسم.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // اختر الإجابة الصحيحة
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
                // صح أو خطأ
                [
                    'title' => 'الفعل المضارع يدل على حدث وقع في الماضي.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                // أكمل الفراغ
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
                // صح أو خطأ
                [
                    'title' => 'الضمير (هو) يعود على المفرد المذكر الغائب.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // أكمل الفراغ
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
                // اختر الإجابة الصحيحة
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
                // صح أو خطأ
                [
                    'title' => 'المثنى يدل على شخصين أو شيئين.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // أكمل الفراغ
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
                // اختر الإجابة الصحيحة
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
                // أكمل الفراغ
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
                // اختر الإجابة الصحيحة
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
                // ========== تم تعليق الأسئلة الزائدة (8 أسئلة) ==========
                // [
                //     'title' => 'الفاعل دائمًا يأتي بعد الفعل.',
                //     'type' => 'true_false',
                //     'is_correct' => false,
                //     'language' => 'ar',
                // ],
                // [
                //     'title' => 'نوع كلمة (مسرور) هو ________.',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'فعل', 'is_correct' => false],
                //         ['text' => 'اسم فاعل', 'is_correct' => false],
                //         ['text' => 'اسم مفعول', 'is_correct' => false],
                //         ['text' => 'صفة', 'is_correct' => true],
                //     ],
                // ],
                // [
                //     'title' => 'الفاعل في الجملة هو من ________ الفعل.',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'يقع عليه', 'is_correct' => false],
                //         ['text' => 'يقوم به', 'is_correct' => true],
                //         ['text' => 'ينتهي به', 'is_correct' => false],
                //         ['text' => 'يصفه', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'جمع المؤنث السالم ينتهي غالبًا بـ ________.',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'ان', 'is_correct' => false],
                //         ['text' => 'ون', 'is_correct' => false],
                //         ['text' => 'ات', 'is_correct' => true],
                //         ['text' => 'ين', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'معنى كلمة (واسع):',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'ضيق', 'is_correct' => false],
                //         ['text' => 'كبير المساحة', 'is_correct' => true],
                //         ['text' => 'قصير', 'is_correct' => false],
                //         ['text' => 'صغير', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'الكلمة التي تعد اسم مفعول هي:',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'مكتوب', 'is_correct' => true],
                //         ['text' => 'كاتب', 'is_correct' => false],
                //         ['text' => 'لاعب', 'is_correct' => false],
                //         ['text' => 'نائم', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'مرادف كلمة (سريع):',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'بطيء', 'is_correct' => false],
                //         ['text' => 'قوي', 'is_correct' => false],
                //         ['text' => 'نشيط', 'is_correct' => false],
                //         ['text' => 'حثيث', 'is_correct' => true],
                //     ],
                // ],
                // [
                //     'title' => 'ضد كلمة (قريب):',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'بعيد', 'is_correct' => true],
                //         ['text' => 'آخر', 'is_correct' => false],
                //         ['text' => 'ضيق', 'is_correct' => false],
                //         ['text' => 'قليل', 'is_correct' => false],
                //     ],
                // ],
            ],
        ];
    }

    /**
     * Assignment 3: Exam Unit One (امتحان)
     */
    private function getExamUnitOneAssignment(): array
    {
        return [
            'title_ar' => 'Exam Unit One',
            'title_en' => 'Exam Unit One',
            'type' => 'exam',
            'duration' => 40,
            'total_xp' => 50,
            'total_coins' => 25,
            'total_marks' => 50,
            'questions_count' => 12,
            'questions' => [
                // تم إعادة ترتيب الأسئلة واختيار 12 سؤال (3 true_false + 5 choice + 4 arrange)
                // صح أو خطأ
                [
                    'title' => 'The word "cat" is a noun.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'en',
                ],
                // أكمل الفراغ
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
                // أعد ترتيب الكلمات
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
                // صح أو خطأ
                [
                    'title' => 'Adjectives describe nouns.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'en',
                ],
                // أكمل الفراغ
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
                // أعد ترتيب الكلمات
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
                // اختر الإجابة الصحيحة
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
                // صح أو خطأ
                [
                    'title' => 'A sentence must start with a capital letter.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'en',
                ],
                // أعد ترتيب الكلمات
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
                // أكمل الفراغ
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
                // أعد ترتيب الكلمات
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
                // اختر الإجابة الصحيحة
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
                // ========== تم تعليق الأسئلة الزائدة (13 سؤال) ==========
                // [
                //     'title' => 'The verb "run" is in the past tense.',
                //     'type' => 'true_false',
                //     'is_correct' => false,
                //     'language' => 'en',
                // ],
                // [
                //     'title' => 'The opposite of "big" is "tall."',
                //     'type' => 'true_false',
                //     'is_correct' => false,
                //     'language' => 'en',
                // ],
                // [
                //     'title' => 'My favorite color is ________.',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'fast', 'is_correct' => false],
                //         ['text' => 'blue', 'is_correct' => true],
                //         ['text' => 'run', 'is_correct' => false],
                //         ['text' => 'slowly', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'We ________ pizza yesterday.',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'Eat', 'is_correct' => false],
                //         ['text' => 'Eating', 'is_correct' => false],
                //         ['text' => 'Ate', 'is_correct' => true],
                //         ['text' => 'Eats', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'A ________ is a place where we read books.',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'School', 'is_correct' => false],
                //         ['text' => 'Library', 'is_correct' => true],
                //         ['text' => 'Hospital', 'is_correct' => false],
                //         ['text' => 'Park', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'They are ________ soccer now.',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'Play', 'is_correct' => false],
                //         ['text' => 'Played', 'is_correct' => false],
                //         ['text' => 'Plays', 'is_correct' => false],
                //         ['text' => 'Playing', 'is_correct' => true],
                //     ],
                // ],
                // [
                //     'title' => 'What is the correct plural of "box"?',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'Boxs', 'is_correct' => false],
                //         ['text' => 'Boxies', 'is_correct' => false],
                //         ['text' => 'Boxes', 'is_correct' => true],
                //         ['text' => 'Boxen', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => '"He is a smart boy." The word "smart" is a:',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'Noun', 'is_correct' => false],
                //         ['text' => 'Verb', 'is_correct' => false],
                //         ['text' => 'Adjective', 'is_correct' => true],
                //         ['text' => 'Adverb', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'Which word is a verb?',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'Teacher', 'is_correct' => false],
                //         ['text' => 'Dance', 'is_correct' => true],
                //         ['text' => 'Table', 'is_correct' => false],
                //         ['text' => 'Happy', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => '"The cat is under the table." The word "under" is a:',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'Noun', 'is_correct' => false],
                //         ['text' => 'Verb', 'is_correct' => false],
                //         ['text' => 'Preposition', 'is_correct' => true],
                //         ['text' => 'Adjective', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'Which of the following is a complete sentence?',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'The big', 'is_correct' => false],
                //         ['text' => 'Running fast', 'is_correct' => false],
                //         ['text' => 'The dog barked', 'is_correct' => true],
                //         ['text' => 'The blue', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'What is the correct possessive form? "The bag of Sara"',
                //     'type' => 'choice',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'Saras bag', 'is_correct' => false],
                //         ['text' => 'Sara bag', 'is_correct' => false],
                //         ['text' => 'Sara\'s bag', 'is_correct' => true],
                //         ['text' => 'Saran bag', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => '( eats – every – breakfast – he – tall – day )',
                //     'type' => 'arrange',
                //     'language' => 'en',
                //     'options' => [
                //         ['text' => 'He', 'order' => 1],
                //         ['text' => 'eats', 'order' => 2],
                //         ['text' => 'breakfast', 'order' => 3],
                //         ['text' => 'every', 'order' => 4],
                //         ['text' => 'day', 'order' => 5],
                //     ],
                // ],
            ],
        ];
    }

    /**
     * Assignment 4: اسم الفاعل (تمرين)
     */
    private function getActiveParticipleAssignment(): array
    {
        return [
            'title_ar' => 'اسم الفاعل',
            'title_en' => 'Active Participle',
            'type' => 'training',
            'total_xp' => 32,
            'total_coins' => 16,
            'total_marks' => 32,
            'questions_count' => 12,
            'questions' => [
                // تم إعادة ترتيب الأسئلة واختيار 12 سؤال (3 true_false + 9 choice)
                // صح أو خطأ
                [
                    'title' => 'اسم الفاعل يدل على من يقوم بالفعل.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // أكمل الفراغ
                [
                    'title' => 'اسم الفاعل من الفعل (لعب) هو ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'لاعب', 'is_correct' => true],
                        ['text' => 'ملعوب', 'is_correct' => false],
                        ['text' => 'يلعب', 'is_correct' => false],
                        ['text' => 'لعبة', 'is_correct' => false],
                    ],
                ],
                // صح أو خطأ
                [
                    'title' => 'يُصاغ اسم الفاعل من الفعل الثلاثي على وزن فاعل.',
                    'type' => 'true_false',
                    'is_correct' => true,
                    'language' => 'ar',
                ],
                // اختر الإجابة الصحيحة
                [
                    'title' => 'اسم الفاعل من الفعل (جلس) هو:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'جالس', 'is_correct' => true],
                        ['text' => 'مجلوس', 'is_correct' => false],
                        ['text' => 'جلس', 'is_correct' => false],
                        ['text' => 'يجلس', 'is_correct' => false],
                    ],
                ],
                // أكمل الفراغ
                [
                    'title' => 'كلمة (ناجح) تُعد ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'فعلًا ماضيًا', 'is_correct' => false],
                        ['text' => 'اسم مفعول', 'is_correct' => false],
                        ['text' => 'اسم فاعل', 'is_correct' => true],
                        ['text' => 'ظرفًا', 'is_correct' => false],
                    ],
                ],
                // صح أو خطأ
                [
                    'title' => 'كلمة "مسافر" ليست اسم فاعل.',
                    'type' => 'true_false',
                    'is_correct' => false,
                    'language' => 'ar',
                ],
                // اختر الإجابة الصحيحة
                [
                    'title' => 'كلمة (راكض) تدل على:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'زمان', 'is_correct' => false],
                        ['text' => 'مكان', 'is_correct' => false],
                        ['text' => 'من يقوم بالفعل', 'is_correct' => true],
                        ['text' => 'آلة', 'is_correct' => false],
                    ],
                ],
                // أكمل الفراغ
                [
                    'title' => 'اسم الفاعل من الفعل (سمع) هو ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'مسموع', 'is_correct' => false],
                        ['text' => 'سمع', 'is_correct' => false],
                        ['text' => 'يسمع', 'is_correct' => false],
                        ['text' => 'سامع', 'is_correct' => true],
                    ],
                ],
                // اختر الإجابة الصحيحة
                [
                    'title' => 'أي الكلمات التالية ليست اسم فاعل؟',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'كاتب', 'is_correct' => false],
                        ['text' => 'لاعب', 'is_correct' => false],
                        ['text' => 'مكتوب', 'is_correct' => true],
                        ['text' => 'سامع', 'is_correct' => false],
                    ],
                ],
                // أكمل الفراغ
                [
                    'title' => 'صيغة اسم الفاعل من الفعل الثلاثي تأتي غالبًا على وزن ________.',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'فعيل', 'is_correct' => false],
                        ['text' => 'فاعل', 'is_correct' => true],
                        ['text' => 'مفعول', 'is_correct' => false],
                        ['text' => 'فاعلة', 'is_correct' => false],
                    ],
                ],
                // اختر الإجابة الصحيحة
                [
                    'title' => 'اسم الفاعل من الفعل (فتح) هو:',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'مفتوح', 'is_correct' => false],
                        ['text' => 'فاتح', 'is_correct' => true],
                        ['text' => 'فتح', 'is_correct' => false],
                        ['text' => 'يفْتَح', 'is_correct' => false],
                    ],
                ],
                // اختر الإجابة الصحيحة
                [
                    'title' => 'أي الجمل التالية تحتوي على اسم فاعل؟',
                    'type' => 'choice',
                    'language' => 'ar',
                    'options' => [
                        ['text' => 'الطفلُ يجري بسرعة', 'is_correct' => false],
                        ['text' => 'الطالبُ مجتهدٌ', 'is_correct' => false],
                        ['text' => 'الصانعُ ماهرٌ', 'is_correct' => true],
                        ['text' => 'كان الجوُّ جميلًا', 'is_correct' => false],
                    ],
                ],
                // ========== تم تعليق الأسئلة الزائدة (4 أسئلة) ==========
                // [
                //     'title' => 'اسم الفاعل دائمًا يكون منصوبًا.',
                //     'type' => 'true_false',
                //     'is_correct' => false,
                //     'language' => 'ar',
                // ],
                // [
                //     'title' => 'أي من التالي مثال لاسم فاعل يدل على مهنة؟',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'طائر', 'is_correct' => false],
                //         ['text' => 'كاتب', 'is_correct' => true],
                //         ['text' => 'ساجد', 'is_correct' => false],
                //         ['text' => 'نائم', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'اسم الفاعل من الفعل (نصرَ) هو:',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'ناصرٌ', 'is_correct' => true],
                //         ['text' => 'منصور', 'is_correct' => false],
                //         ['text' => 'ينصر', 'is_correct' => false],
                //         ['text' => 'نصر', 'is_correct' => false],
                //     ],
                // ],
                // [
                //     'title' => 'اسم الفاعل من الفعل (حفظَ) هو:',
                //     'type' => 'choice',
                //     'language' => 'ar',
                //     'options' => [
                //         ['text' => 'حافظٌ', 'is_correct' => true],
                //         ['text' => 'محفوظ', 'is_correct' => false],
                //         ['text' => 'يحفظ', 'is_correct' => false],
                //         ['text' => 'حفظ', 'is_correct' => false],
                //     ],
                // ],
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
            'description' => $assignmentData['type'] === 'exam' ? "Exam: {$assignmentData['title_en']}" : "Training for {$assignmentData['title_en']}",
            'description_ar' => $assignmentData['type'] === 'exam' ? "امتحان: {$assignmentData['title_ar']}" : "تدريب لـ {$assignmentData['title_ar']}",
            'type' => $assignmentData['type'],
            'duration' => $assignmentData['duration'] ?? ($assignmentData['type'] === 'exam' ? 60 : null),
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

        // Use values from questionData if available, otherwise use passed parameters
        $questionXp = $questionData['xp'] ?? $xp;
        $questionCoins = $questionData['coins'] ?? $coins;
        $questionMarks = $questionData['marks'] ?? $marks;

        // Create the question
        $question = Question::create([
            'exam_training_id' => $examTrainingId,
            'title' => $questionData['title'],
            'type' => $type,
            'language' => $language,
            'xp' => $questionXp,
            'coins' => $questionCoins,
            'marks' => $questionMarks,
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

    /**
     * Generate questions from book pages (آدم يتخيل النحلة)
     */
    private function generateBookQuestions($pages): array
    {
        $questions = [];

        // Question 1: About the main character
        $questions[] = [
            'title' => 'ما اسم الشخصية الرئيسية في القصة؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'سناء', 'is_correct' => false],
                ['text' => 'آدم', 'is_correct' => true],
                ['text' => 'راغب', 'is_correct' => false],
                ['text' => 'مشمسة', 'is_correct' => false],
            ],
        ];

        // Question 2: About what attracted آدم
        $questions[] = [
            'title' => 'ما الذي جذب آدم في البداية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'صوت النحلة', 'is_correct' => false],
                ['text' => 'رائحة الزهور المتفتحة', 'is_correct' => true],
                ['text' => 'ألوان الزهور', 'is_correct' => false],
                ['text' => 'شكل النحلة', 'is_correct' => false],
            ],
        ];

        // Question 3: About the bee description (brother)
        $questions[] = [
            'title' => 'كيف وصف أخ آدم النحلة في البداية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'حشرة مفيدة', 'is_correct' => false],
                ['text' => 'حشرة تطير كالذبابة، لها إبرة صغيرة وتقرص', 'is_correct' => true],
                ['text' => 'حشرة ملونة بالأصفر والأسود', 'is_correct' => false],
                ['text' => 'حشرة تصنع العسل', 'is_correct' => false],
            ],
        ];

        // Question 4: True/False about honey
        $questions[] = [
            'title' => 'العسل يأتي من النحلة.',
            'type' => 'true_false',
            'is_correct' => true,
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
        ];

        // Question 5: About what آدم thought about honey
        $questions[] = [
            'title' => 'ماذا كان يعتقد آدم عن مصدر العسل في البداية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'يأتي من النحلة', 'is_correct' => false],
                ['text' => 'ماما تصنعه', 'is_correct' => true],
                ['text' => 'يأتي من الزهور', 'is_correct' => false],
                ['text' => 'يأتي من المتجر', 'is_correct' => false],
            ],
        ];

        // Question 6: About the father's explanation
        $questions[] = [
            'title' => 'كيف وصف الأب النحلة؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'حشرة مخيفة', 'is_correct' => false],
                ['text' => 'حشرة مفيدة تصنع لنا العسل من الأزهار', 'is_correct' => true],
                ['text' => 'حشرة سوداء', 'is_correct' => false],
                ['text' => 'حشرة تطير فقط', 'is_correct' => false],
            ],
        ];

        // Question 7: About the grandmother's gift
        $questions[] = [
            'title' => 'ماذا أهدت الجدة لآدم؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'كتاب عن النحل', 'is_correct' => false],
                ['text' => 'جوارب صفراء منقطة بالأسود', 'is_correct' => true],
                ['text' => 'لعبة نحلة', 'is_correct' => false],
                ['text' => 'صورة نحلة', 'is_correct' => false],
            ],
        ];

        // Question 8: About bee colors
        $questions[] = [
            'title' => 'ما ألوان النحلة الحقيقية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'سوداء فقط', 'is_correct' => false],
                ['text' => 'ملونة بالأصفر والأسود', 'is_correct' => true],
                ['text' => 'صفراء فقط', 'is_correct' => false],
                ['text' => 'بيضاء', 'is_correct' => false],
            ],
        ];

        // Question 9: True/False about bee appearance
        $questions[] = [
            'title' => 'النحلة تشبه الذبابة تماماً.',
            'type' => 'true_false',
            'is_correct' => false,
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
        ];

        // Question 10: About what آدم asked his mother
        $questions[] = [
            'title' => 'ماذا سأل آدم والدته؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'من أين يأتي العسل؟', 'is_correct' => false],
                ['text' => 'من منهم مخطئ في وصف النحلة؟', 'is_correct' => true],
                ['text' => 'كيف تطير النحلة؟', 'is_correct' => false],
                ['text' => 'ما شكل النحلة؟', 'is_correct' => false],
            ],
        ];

        // Question 11: About the mother's answer
        $questions[] = [
            'title' => 'ماذا قالت الأم لآدم؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'أخوك مخطئ', 'is_correct' => false],
                ['text' => 'لا أحد مخطئ، كل منهم وصف النحلة بشكل صحيح من جانب واحد فقط', 'is_correct' => true],
                ['text' => 'والدك مخطئ', 'is_correct' => false],
                ['text' => 'جدتك مخطئة', 'is_correct' => false],
            ],
        ];

        // Question 12: About how آدم learned the truth
        $questions[] = [
            'title' => 'كيف عرف آدم شكل النحلة الحقيقي؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'من خلال الرسم', 'is_correct' => false],
                ['text' => 'من خلال صورة في كتاب', 'is_correct' => true],
                ['text' => 'من خلال مشاهدة نحلة حقيقية', 'is_correct' => false],
                ['text' => 'من خلال الحلم', 'is_correct' => false],
            ],
        ];

        // Question 13: True/False about bee characteristics
        $questions[] = [
            'title' => 'النحلة لها إبرة صغيرة.',
            'type' => 'true_false',
            'is_correct' => true,
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
        ];

        // Question 14: About what bees eat
        $questions[] = [
            'title' => 'ماذا تأكل النحلة؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'اللحوم', 'is_correct' => false],
                ['text' => 'من الزهور', 'is_correct' => true],
                ['text' => 'الأوراق', 'is_correct' => false],
                ['text' => 'الفواكه', 'is_correct' => false],
            ],
        ];

        // Question 15: About bee's purpose
        $questions[] = [
            'title' => 'لماذا تأكل النحلة من الزهور؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'لأنها جائعة', 'is_correct' => false],
                ['text' => 'كي تصنع العسل', 'is_correct' => true],
                ['text' => 'لأنها تحب الزهور', 'is_correct' => false],
                ['text' => 'لأنها لا تجد طعاماً آخر', 'is_correct' => false],
            ],
        ];

        return $questions;
    }

    /**
     * Create assignment from book (آدم يتخيل النحلة) - New separate method
     */
    private function createAdamBeeBookAssignment(int $teacherId): void
    {
        // Generate folder name from title
        $folderName = $this->titleToSlug('آدم يتخيل النحلة');

        // Find the book
        $book = Book::create([
            'title' => 'آدم يتخيل النحلة',
            'cover' => "books/{$folderName}/cover.svg",
            'thumbnail' => "books/{$folderName}/thumbnail.jpg",
            'is_in_library' => false,
            'language' => 'ar',
            'has_sound' => true,
            'xp' => 0,
            'coins' => 0,
            'marks' => 0,
        ]);

        // create this pages
        $pages = [
            'جَذَبَتْ آدَمَ رَائِحَةُ الزُّهُورِ الْمُتَفَتِّحَةِ بِجانِبِ مَنْزِلِهِ؛ فَاقْتَرَبَ مِنْها لِيَشُمَّها؛ فَحَذَرَهُ أَخوهُ: اِنْتَبِه قَدْ تَكونُ هُناكَ نَحْلَةٌ دَاخِلَ الزَّهْرَةِ. سَأَلَ آدَمُ: مَا هِيَ النَّحْلَةُ؟ أَجَابَ الْأَخُ: حَشَرَةٌ تَطيرُ كالذُّبابَةِ، لَهَا إِبْرَةٌ صغيرةٌ، وَتَقْرُصُ قَرْصَةً مُؤْلِمَةً إِنْ أَزْعَجْتَها.',
            'تَخَيَّلَ آدَمُ النَّحْلَةَ حَشَرَةً سَوْداءَ مُخيفَةً، شَكْلُها كالذُّبابَةِ، لَكِنَّ لَهَا إِبْرَةً كَبِيرَةً تَقْرُصُ النَّاسَ.',
            'في يَوْمٍ لاحق كانَ آدَمُ يَتَناوَلُ مَعَ وَالِدِهِ خُبْرًا بِالْعَسَلِ، فَسَأَلَهُ والِدُهُ: هَلْ تَعْلَمُ مِنْ أَيْنَ يَأْتِي الْعَسَلُ؟ أَجَابَ آدَمُ: ماما تَصْنَعُهُ؛ فَعَلَّقَ الْأَبُ الْعَسَلُ يَأْتِي مِنَ النَّحْلَةِ اعْتَرَضَ آدَمُ قَائِلًا: لَكِنَّ النَّحْلَةَ تَقْرُصُ النَّاسَ، أَنا لا أُحِبُّها فَوَضَّحَ الْأَبُ: النَّحْلَةُ حَشَرَةٌ مُفِيدَةٌ تَصْنَعُ لَنا الْعَسَلَ مِنَ الْأَزْهَارِ.',
            'تَخَيَّلَ آدَمُ النَّحْلَةَ مَرَّةً أُخْرى، حَشَرَةً سَوْداءَ لكِنَّها لَيْسَتْ مُخيفَةً، شَكْلُهَا كالذُّبابَةِ، تَصْنَعُ الْعَسَلَ، وَلَهَا إِبْرَةٌ صَغِيرَةٌ.',
            'وَفِي يَوْمِ آخَرَ، جَاءَتِ الْجَدَّةُ لِزِيارَةِ الْعَائِلَةِ، وَأَحْضَرَتْ لِآدَمَ هَدِيَّةً جَميلَةً: جَوارِبَ صَفْراءَ مُنَقَّطَةً بِالْأَسْوَدِ. شَكَرَ آدَمُ جَدَّتَهُ. قَالَتِ الْجَدَّةُ: أَلْوانُهَا جَمِيلَةٌ كَأَلْوانِ النَّحْلَةِ، سَأَلَهَا آدَمُ: أَلَيْسَتِ النَّحْلَةُ سَوْداءَ كالذُّبابَةِ؟ قَالَتِ الْجَدَّةُ: لا، بَلْ مُلَوَّنَةٌ بِالْأَصْفَرِ وَالْأَسْوَدِ.',
            'تَخَيَّلَ آدَمُ النَّحْلَةَ هَذِهِ الْمَرَّةَ أَيْضًا كالذُّبابَةِ، لَكِنَّها لَمْ تَكُنْ سَوْدَاءَ، بَلْ كَانَتْ صَفْرَاءَ مُنَقَّطَةً بِالْأَسْوَدِ.',
            'في الْيَوْمِ التالي، قَرَّرَ آدَمُ أَنْ يَرْسُمَ نَحْلَةً فِي دَفْتَرِهِ، لَكِنَّهُ اخْتارَ بَيْنَ كُلِّ ما قيلَ لَهُ، فَسَأَلَ والِدَتَهُ: ماما، أخي يَقولُ: إِنَّ النَّحْلَةَ كالذُّبَابَةِ لَكِنَّها تَقْرُصُ، وبابا يَقولُ: إِنَّها مُفِيدَةٌ وَتَصْنَعُ الْعَسَلَ، وَجَدَّتِي تَقُولُ: إِنَّهَا مُلَوَّنَةٌ بِالْأَصْفَرِ وَالْأَسْوَدِ؛ فَمَنْ مِنْهُمْ مُخْطِةٌ؟',
            'قالَتِ الْأُمُ: لَا أَحَدَ مُخْطِةٌ؛ كُلٌّ مِنْهُمْ قَدْ وَصَفَ النَّحْلَةَ بِشَكْلٍ صَحِيحٍ، لَكِنَّهُ وَصَفَها مِنْ جَانِبِ وَاحِدٍ فَقَطْ تَعَالَ لِأُرِيَكَ صورتها.',
            'فَتَحَتِ الْأُمُّ كِتابًا فِيهِ صُوَرٌ مُلَوَّنَةٌ، وَعَرَضَتْ لِآدَمَ صورَةَ النَّحْلَةِ. عَرَفَ آدَمُ شَكْلَ النَّحْلَةِ أَخيرًا، وَقَرَّرَ أَنْ يَرْسُمَهَا فَيَجْمَعَ كُلَّ الصِّفاتِ السَّابِقَةِ الَّتِي قِيلَتْ لَهُ.',
            'مُخَطَّطَةٌ بِالْأَصْفَرِ وَالْأَسْوَدِ، لَهَا إِبْرَةٌ صَغِيرَةٌ. تَأْكُلُ مِنَ الزُّهُورِ؛ كَيْ تَصْنَعَ الْعَسَلَ. لَكِنَّهَا لَمْ تَكُنْ تُشْبِهُ الذُّبَابَةَ أَبَدًا هَذِهِ الْمَرَّةَ.',
        ];

        foreach ($pages as $index => $page) {
            $pageNumber = $index + 1;

            $book->pages()->create([
                'text' => $page,
                'image' => "books/{$folderName}/pages/page_{$pageNumber}/image.png",
                'mp3' => $book->has_sound ? "books/{$folderName}/pages/page_{$pageNumber}/audio.mp3" : null,
                'is_text_to_speech' => !$book->has_sound,
            ]);
        }

        // Get book pages
        $pages = Page::where('book_id', $book->id)->orderBy('id')->get();

        if ($pages->isEmpty()) {
            $this->command->warn('⚠️  No pages found for book "آدم يتخيل النحلة". Skipping book assignment creation.');
            return;
        }

        // Create questions based on book pages
        $questions = $this->generateAdamBeeQuestions($pages);

        // Calculate totals
        $questionsCount = count($questions);
        $totalXp = $book->xp ?? 100;
        $totalCoins = $book->coins ?? 50;
        $totalMarks = $book->marks ?? 75;

        // Create ExamTraining related to the book
        $training = ExamTraining::create([
            'title' => 'Training: ' . $book->title,
            'title_ar' => 'تدريب كتاب: ' . $book->title,
            'description' => "Training based on the book: {$book->title}",
            'description_ar' => "تدريب مبني على كتاب: {$book->title}",
            'type' => 'training',
            'duration' => null,
            'created_by' => $teacherId,
            'subject_id' => $book->subject_id,
            'group_id' => null,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(2),
        ]);

        // Link training to book
        $book->update(['related_training_id' => $training->id]);

        $this->command->info("📝 Created training from book: {$book->title}");
        $this->command->info("   🔗 Linked training to book (related_training_id)");

        // Calculate points per question
        $xpPerQuestion = (int) ($totalXp / $questionsCount);
        $coinsPerQuestion = (int) ($totalCoins / $questionsCount);
        $marksPerQuestion = (int) ($totalMarks / $questionsCount);

        // Create questions
        $questionCount = 0;
        foreach ($questions as $questionData) {
            $this->createQuestion(
                $training->id,
                $questionData,
                $xpPerQuestion,
                $coinsPerQuestion,
                $marksPerQuestion
            );
            $questionCount++;
        }

        $this->command->info("   ✅ Created {$questionCount} questions from book pages");

        // Create Assignment with assignable_type = 'book'
        $assignment = Assignment::create([
            'title_ar' => 'تمرين كتاب: ' . $book->title,
            'title_en' => 'Book Exercise: ' . $book->title,
            'assignable_type' => 'book',
            'assignable_id' => $book->id,
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
        $this->command->info("   📚 Assignment type: book (related to book ID: {$book->id})");
        $this->command->info("   👤 Assigned to student ID: {$studentId}");
        $this->command->newLine();
    }

    /**
     * Generate questions from book pages (آدم يتخيل النحلة)
     */
    private function generateAdamBeeQuestions($pages): array
    {
        $questions = [];

        // تم إعادة ترتيب الأسئلة واختيار 12 سؤال (9 choice + 3 true_false)
        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'ما اسم الشخصية الرئيسية في القصة؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'سناء', 'is_correct' => false],
                ['text' => 'آدم', 'is_correct' => true],
                ['text' => 'راغب', 'is_correct' => false],
                ['text' => 'مشمسة', 'is_correct' => false],
            ],
        ];

        // صح أو خطأ
        $questions[] = [
            'title' => 'العسل يأتي من النحلة.',
            'type' => 'true_false',
            'is_correct' => true,
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'ما الذي جذب آدم في البداية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'صوت النحلة', 'is_correct' => false],
                ['text' => 'رائحة الزهور المتفتحة', 'is_correct' => true],
                ['text' => 'ألوان الزهور', 'is_correct' => false],
                ['text' => 'شكل النحلة', 'is_correct' => false],
            ],
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'كيف وصف أخ آدم النحلة في البداية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'حشرة مفيدة', 'is_correct' => false],
                ['text' => 'حشرة تطير كالذبابة، لها إبرة صغيرة وتقرص', 'is_correct' => true],
                ['text' => 'حشرة ملونة بالأصفر والأسود', 'is_correct' => false],
                ['text' => 'حشرة تصنع العسل', 'is_correct' => false],
            ],
        ];

        // صح أو خطأ
        $questions[] = [
            'title' => 'النحلة تشبه الذبابة تماماً.',
            'type' => 'true_false',
            'is_correct' => false,
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'ماذا كان يعتقد آدم عن مصدر العسل في البداية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'يأتي من النحلة', 'is_correct' => false],
                ['text' => 'ماما تصنعه', 'is_correct' => true],
                ['text' => 'يأتي من الزهور', 'is_correct' => false],
                ['text' => 'يأتي من المتجر', 'is_correct' => false],
            ],
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'كيف وصف الأب النحلة؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'حشرة مخيفة', 'is_correct' => false],
                ['text' => 'حشرة مفيدة تصنع لنا العسل من الأزهار', 'is_correct' => true],
                ['text' => 'حشرة سوداء', 'is_correct' => false],
                ['text' => 'حشرة تطير فقط', 'is_correct' => false],
            ],
        ];

        // صح أو خطأ
        $questions[] = [
            'title' => 'النحلة لها إبرة صغيرة.',
            'type' => 'true_false',
            'is_correct' => true,
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'ماذا أهدت الجدة لآدم؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'كتاب عن النحل', 'is_correct' => false],
                ['text' => 'جوارب صفراء منقطة بالأسود', 'is_correct' => true],
                ['text' => 'لعبة نحلة', 'is_correct' => false],
                ['text' => 'صورة نحلة', 'is_correct' => false],
            ],
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'ما ألوان النحلة الحقيقية؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'سوداء فقط', 'is_correct' => false],
                ['text' => 'ملونة بالأصفر والأسود', 'is_correct' => true],
                ['text' => 'صفراء فقط', 'is_correct' => false],
                ['text' => 'بيضاء', 'is_correct' => false],
            ],
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'ماذا سأل آدم والدته؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'من أين يأتي العسل؟', 'is_correct' => false],
                ['text' => 'من منهم مخطئ في وصف النحلة؟', 'is_correct' => true],
                ['text' => 'كيف تطير النحلة؟', 'is_correct' => false],
                ['text' => 'ما شكل النحلة؟', 'is_correct' => false],
            ],
        ];

        // اختر الإجابة الصحيحة
        $questions[] = [
            'title' => 'كيف عرف آدم شكل النحلة الحقيقي؟',
            'type' => 'choice',
            'language' => 'ar',
            'xp' => 2,
            'coins' => 1,
            'marks' => 1,
            'options' => [
                ['text' => 'من خلال الرسم', 'is_correct' => false],
                ['text' => 'من خلال صورة في كتاب', 'is_correct' => true],
                ['text' => 'من خلال مشاهدة نحلة حقيقية', 'is_correct' => false],
                ['text' => 'من خلال الحلم', 'is_correct' => false],
            ],
        ];

        // ========== تم تعليق الأسئلة الزائدة (3 أسئلة) ==========
        // $questions[] = [
        //     'title' => 'ماذا قالت الأم لآدم؟',
        //     'type' => 'choice',
        //     'language' => 'ar',
        //     'xp' => 2,
        //     'coins' => 1,
        //     'marks' => 1,
        //     'options' => [
        //         ['text' => 'أخوك مخطئ', 'is_correct' => false],
        //         ['text' => 'لا أحد مخطئ، كل منهم وصف النحلة بشكل صحيح من جانب واحد فقط', 'is_correct' => true],
        //         ['text' => 'والدك مخطئ', 'is_correct' => false],
        //         ['text' => 'جدتك مخطئة', 'is_correct' => false],
        //     ],
        // ],
        // $questions[] = [
        //     'title' => 'ماذا تأكل النحلة؟',
        //     'type' => 'choice',
        //     'language' => 'ar',
        //     'xp' => 2,
        //     'coins' => 1,
        //     'marks' => 1,
        //     'options' => [
        //         ['text' => 'اللحوم', 'is_correct' => false],
        //         ['text' => 'من الزهور', 'is_correct' => true],
        //         ['text' => 'الأوراق', 'is_correct' => false],
        //         ['text' => 'الفواكه', 'is_correct' => false],
        //     ],
        // ],
        // $questions[] = [
        //     'title' => 'لماذا تأكل النحلة من الزهور؟',
        //     'type' => 'choice',
        //     'language' => 'ar',
        //     'xp' => 2,
        //     'coins' => 1,
        //     'marks' => 1,
        //     'options' => [
        //         ['text' => 'لأنها جائعة', 'is_correct' => false],
        //         ['text' => 'كي تصنع العسل', 'is_correct' => true],
        //         ['text' => 'لأنها تحب الزهور', 'is_correct' => false],
        //         ['text' => 'لأنها لا تجد طعاماً آخر', 'is_correct' => false],
        //     ],
        // ],

        return $questions;
    }

    /**
     * Convert Arabic title to English slug for folder naming
     */
    private function titleToSlug(string $title): string
    {
        if (class_exists('Transliterator')) {
            $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
            if ($transliterator) {
                $latinText = $transliterator->transliterate($title);
                return Str::slug($latinText);
            }
        }

        return Str::slug($title);
    }
}
