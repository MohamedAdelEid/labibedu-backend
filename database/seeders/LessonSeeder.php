<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Lesson;
use App\Infrastructure\Models\Grade;
use App\Infrastructure\Models\Subject;
use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Page;
use App\Infrastructure\Models\LessonCategory;
use App\Infrastructure\Models\ExamTraining;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📚 Starting Lessons Seeding...');

        $lessonsData = $this->getLessonsData();

        foreach ($lessonsData as $lessonData) {
            $this->createLesson($lessonData);
        }

        $this->command->info('✅ Lessons seeded successfully!');
        $this->command->info('📊 Total lessons created: ' . count($lessonsData));
    }

    /**
     * Get all lessons data configuration
     * 
     * To add a new lesson, simply add a new array to this method
     */
    private function getLessonsData(): array
    {
        return [
            $this->getAreAllPlanetsRockyLesson(),
            $this->getHoopoeAndQueenOfShebaLesson(),
            $this->getMushroomCultivationLesson(),
            $this->getRecyclingLesson(),
            // Add more lessons here in the future
        ];
    }

    /**
     * Lesson: Are all the planets rocky?
     */
    private function getAreAllPlanetsRockyLesson(): array
    {
        return [
            'title' => 'Are all the planets rocky?',
            'category_name_en' => 'Beginner',
            'grade_name' => 'Grade One',
            'grade_level' => 'primary', // primary level
            'subject_name_en' => 'English',
            'is_in_library' => false,
            'training' => [
                'title' => 'Training: Are all the planets rocky?',
                'title_ar' => 'تدريب: هل كل الكواكب صخرية؟',
                'description' => 'Training exercises for the lesson Are all the planets rocky?',
                'description_ar' => 'تمارين تدريبية لدرس هل كل الكواكب صخرية؟',
                'questions' => [
                    // A. True / False Questions (5 questions)
                    [
                        'title' => 'Not all the planets in our solar system are rocky.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'The rocky planets are the farthest planets from the Sun.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'There are volcanoes, valleys and craters on the rocky planets.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'The Moon is a planet because it goes around the Sun.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    [
                        'title' => 'Scientists discovered another rocky planet outside our solar system.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                    ],
                    // B. Multiple Choice Questions (10 questions)
                    [
                        'title' => 'How many rocky planets are there in our solar system?',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Two', 'is_correct' => false],
                            ['text' => 'Four', 'is_correct' => true],
                            ['text' => 'Six', 'is_correct' => false],
                            ['text' => 'Eight', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'Which of the following is a rocky planet?',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Saturn', 'is_correct' => false],
                            ['text' => 'Neptune', 'is_correct' => false],
                            ['text' => 'Earth', 'is_correct' => true],
                            ['text' => 'Uranus', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'What is the core of the rocky planets mostly made of?',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Ice', 'is_correct' => false],
                            ['text' => 'Gas', 'is_correct' => false],
                            ['text' => 'Rock only', 'is_correct' => false],
                            ['text' => 'Metal', 'is_correct' => true],
                        ],
                    ],
                    [
                        'title' => 'What did the scientists use to discover a rocky planet outside our solar system?',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Airplanes', 'is_correct' => false],
                            ['text' => 'Satellites', 'is_correct' => false],
                            ['text' => 'A powerful space telescope', 'is_correct' => true],
                            ['text' => 'A rocket', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'Why is the Moon not considered a planet?',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'It is too small', 'is_correct' => false],
                            ['text' => 'It doesn\'t go around the Sun', 'is_correct' => true],
                            ['text' => 'It is made of rock', 'is_correct' => false],
                            ['text' => 'It is too close to Earth', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'The four rocky planets are Mercury, Venus, Earth and ________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Jupiter', 'is_correct' => false],
                            ['text' => 'Neptune', 'is_correct' => false],
                            ['text' => 'Mars', 'is_correct' => true],
                            ['text' => 'Saturn', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'The rocky planets have a ________ surface.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Gassy', 'is_correct' => false],
                            ['text' => 'Watery', 'is_correct' => false],
                            ['text' => 'Soft', 'is_correct' => false],
                            ['text' => 'Hard', 'is_correct' => true],
                        ],
                    ],
                    [
                        'title' => 'Scientists discovered a rocky planet outside our ________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Galaxy', 'is_correct' => false],
                            ['text' => 'Solar system', 'is_correct' => true],
                            ['text' => 'Moon', 'is_correct' => false],
                            ['text' => 'Universe', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'The Moon is ________, but it isn\'t a planet.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Gassy', 'is_correct' => false],
                            ['text' => 'Icy', 'is_correct' => false],
                            ['text' => 'Rocky', 'is_correct' => true],
                            ['text' => 'Watery', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'The Moon goes around the ________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'en',
                        'options' => [
                            ['text' => 'Sun', 'is_correct' => false],
                            ['text' => 'Mars', 'is_correct' => false],
                            ['text' => 'Earth', 'is_correct' => true],
                            ['text' => 'Venus', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'book' => [
                'title' => 'Are all the planets rocky?',
                'language' => 'en',
                'has_sound' => false,
                'pages' => [
                    [
                        'text' => '<strong>Are all the planets rocky?</strong><br><br>Not all of the eight planets in our solar system are rocky. Only four planets are made of rocks. They are the planets nearest to the Sun: Mercury, Venus, Earth and Mars. These four planets have got a hard, rocky surface, and their core is mostly made of metal. Space scientists know there are volcanoes, valleys and craters on the surface of the rocky planets. They also know that Mars has a very high mountain.',
                    ],
                    [
                        'text' => 'Scientists found out that there is another rocky planet outside our solar system. They saw it with a strong telescope in space. Perhaps there are more rocky planets in other solar systems, too.<br><br>And what\'s the Moon like? The Moon is rocky, but it isn\'t a planet. This is because the Moon doesn\'t go around the Sun; the Moon goes around the Earth.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Lesson: قصة الهدهد وملكة سبأ
     */
    private function getHoopoeAndQueenOfShebaLesson(): array
    {
        return [
            'title' => 'قصة الهدهد وملكة سبأ',
            'category_name_en' => 'Beginner',
            'grade_name' => 'Grade One',
            'grade_level' => 'primary',
            'subject_name_en' => 'Arabic',
            'is_in_library' => false,
            'training' => [
                'title' => 'Training: قصة الهدهد وملكة سبأ',
                'title_ar' => 'تدريب: قصة الهدهد وملكة سبأ',
                'description' => 'Training exercises for the lesson قصة الهدهد وملكة سبأ',
                'description_ar' => 'تمارين تدريبية لدرس قصة الهدهد وملكة سبأ',
                'questions' => [
                    // أولًا: أسئلة صح / خطأ (5 أسئلة)
                    [
                        'title' => 'خرج الهدهد بعيدًا عن عشه واقترب من مملكة سبأ في اليمن.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'كانت ملكة سبأ لا تستشير وزراءها في شؤون المملكة.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'رأى الهدهد قوم سبأ يعبدون الشمس من دون الله.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'قبل سليمان –عليه السلام– الهدية التي أرسلتها الملكة.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'عندما رأت الملكة عرشها أمام سليمان قالت: (لا، هذا ليس عرشي).',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    // ثانيًا: أسئلة اختيار من متعدد (5 أسئلة)
                    [
                        'title' => 'أين تقع مملكة سبأ؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'العراق', 'is_correct' => false],
                            ['text' => 'اليمن', 'is_correct' => true],
                            ['text' => 'الشام', 'is_correct' => false],
                            ['text' => 'مصر', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'ماذا رأى الهدهد قوم سبأ يعبدون؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'القمر', 'is_correct' => false],
                            ['text' => 'النار', 'is_correct' => false],
                            ['text' => 'الشمس', 'is_correct' => true],
                            ['text' => 'الأصنام', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'لماذا رفض سليمان –عليه السلام– الهدية؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'لأنها كانت قليلة', 'is_correct' => false],
                            ['text' => 'لأنه لا يقبل الهدايا من الملوك', 'is_correct' => false],
                            ['text' => 'لأنه نبي ويدعوهم لعبادة الله', 'is_correct' => true],
                            ['text' => 'لأنه أراد زيارة المملكة بنفسه', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'ماذا قررت الملكة بعد أن أخبرها الوزير برد سليمان؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'محاربته', 'is_correct' => false],
                            ['text' => 'الهروب من المملكة', 'is_correct' => false],
                            ['text' => 'إرسال جيش كبير', 'is_correct' => false],
                            ['text' => 'الذهاب لمقابلته', 'is_correct' => true],
                        ],
                    ],
                    [
                        'title' => 'كيف أجابت الملكة عندما سألها سليمان: (أهكذا عرشك؟)',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'هذا ليس عرشي', 'is_correct' => false],
                            ['text' => 'كأنه هو', 'is_correct' => true],
                            ['text' => 'هو بالفعل', 'is_correct' => false],
                            ['text' => 'ربما يشبهه فقط', 'is_correct' => false],
                        ],
                    ],
                    // ثالثًا: أكمل الفراغ (5 أسئلة)
                    [
                        'title' => 'خرج الهدهد من عند سليمان –عليه السلام– وذهب إلى مملكة ________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'عاد', 'is_correct' => false],
                            ['text' => 'سبأ', 'is_correct' => true],
                            ['text' => 'ثمود', 'is_correct' => false],
                            ['text' => 'حمير', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'كان سليمان –عليه السلام– يفهم لغة ________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الجن', 'is_correct' => false],
                            ['text' => 'الطير', 'is_correct' => true],
                            ['text' => 'الوحوش', 'is_correct' => false],
                            ['text' => 'البشر فقط', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'اقترح الوزراء على الملكة أن تُرسل ________ لمحاربة سليمان.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'جيشًا كبيرًا', 'is_correct' => true],
                            ['text' => 'وفدًا صغيرًا', 'is_correct' => false],
                            ['text' => 'رجلًا واحدًا', 'is_correct' => false],
                            ['text' => 'أسيرًا', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'أرادت الملكة التأكد من سلامة عرشها فأمرت الحرس ________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'بتدميره', 'is_correct' => false],
                            ['text' => 'بإخفائه', 'is_correct' => false],
                            ['text' => 'بنقله', 'is_correct' => true],
                            ['text' => 'ببيعه', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'عندما رأت الملكة عرشها عند سليمان ازداد يقينها بأنه ________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'ملك', 'is_correct' => false],
                            ['text' => 'ساحر', 'is_correct' => false],
                            ['text' => 'نبي', 'is_correct' => true],
                            ['text' => 'عالم', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'book' => [
                'title' => 'قصة الهدهد وملكة سبأ',
                'language' => 'ar',
                'has_sound' => true,
                'pages' => [
                    [
                        'text' => 'خَرَجَ هُدْهُدُ سُلَيْمانَ -عَلَيْهِ السَّلامُ- وَطارَ بَعيدًا عَنْ مَكانِ عُشِّهِ الَّذي يَسْكُنُ فيهِ، وَاقْتَرَبَ مِنْ مَمْلَكَةِ سَبَاً في بلاد الْيَمَنِ الَّتي تَحْكُمُها مَلِكَةٌ تُعْرَفُ بِتَبادُلِ الرَّأْيِ مَعَ وُزَرائِها، وَوُدِّ قَوْمِها لَها. تَعَجَّبَ الْهُدْهُدُ مِنْ مَمْلَكَةِ سَبَاً، وَمِنْ عَرْشِ الْمَلِكَةِ، وَرَأى الْقَوْمَ يَسْجُدونَ للِشَّمْسِ وَيَعْبُدونَها مِنْ دونِ اللّهِ تَعالى، فَقَرَّرَ أَنْ يَعودَ وَيُخْبِرَ سُلَيْمانَ -عَلَيْهِ السَّلامُ- بذِلِك،َ فقََد كان سلُيَمْان -علَيَْه السلام-ُ يفَهَْم لغَُة الطيَّْر،ِ ويَتَحَدَثَّهُا.',
                        'has_image' => true,
                    ],
                    [
                        'text' => 'وفَي ذلِك اليَْومْ،ِ تلَفََّت سلُيَمْان -علَيَْه السلام-ُ يبَحَْث عَن الْهدُهْدُ فلََم يَجدِهْ،ُ ولََم تَمضْ إلّاِ مُدةَّ مِن الزمَّنَ حتَّى حضََر الْهدُهْدُ،ُ وقَدَ جاء منِ سَباَ بنِبَاَ يقَين،ٍ وبَدَأَ يُخبْرِ سلُيَمْان -علَيَهْ السلام-ُ عنَ الْملَكِةَ وقَوَمْهِا ومَا يعَبْدُون منِ دون الله وعََن العَْرشْ العظيم فاَندْهََش سلُيَمْان-ُعلَيَهْ السلام-ُ، وكَتََب كتِابًا، وأَمََر الْهدُهْدُ أنَ يوصلَِه إلِى الْملَكِةَ.ِ<br><br>طار الْهدُهْدُ متُوَجَهًِّا باِلكْتِاب إلِى مَملْكَةَ سَباَ،ً وكَانتَ الْملَكِةَ أوَلَّ مَن اطلَّعَ علَيَْه،ِ وقَدَ احتْوَى الكْتِاب ما لَم تتَوَقَعَّهْ.ُ إنِ سلُيَمْان -علَيَهْ السلام-ُ يدَعْوها وقَوَمْهَا لعِبِادةَ اللّه وحَْدهَ،ُ وتَرَكْ عبِادةَ الشَّمسْ،ِ فجَمَعَتَ الْوزُرَاء واَلْمسُتْشَارين،َ وعَرَضَتَ عَلَيْهِمُ الْكِتابَ، وَطَلَبَتِ الرَّأْيَ وَالْمَشورَةَ؛ فَاقْتَرَحوا عَلى الْمَلِكَةِ إِرْسالَ جَيْشٍ عَظيمٍ يُحارِبُ سُلَيْمانَ- عَلَيْهِ السَّلامُ-، إِلّّا أَنَّ الْمَلِكَةَ كانَتْ أَكْثَرَ حِكْمَةً، وَخَشِيَتْ خَسارَةَ بَعْضِ مُمْتَلَكاتِها وَمُمْتَلَكاتِ قَوْمِها، وَارْتَأَتْ أَنْ تُرْسِلَ هَدِيَّةً قَيِّمَةً إِلى سُلَيْمانَ -عَلَيْهِ السَّلامُ- مَعَ أَحَدِ الْوُزَراءِ.',
                        'has_image' => false,
                    ],
                    [
                        'text' => 'وَأَمّا سُلَيْمانُ -عَلَيْهِ السَّلامُ- فَقَدْ رَفَضَ الْهَدِيَّةَ، وَأَخْبَرَ الْوَزيرَ أَنَّ عَلَيْهِمْ أَنْ يَتْرُكوا عِبادَةَ الشَّمْسِ، وَأَنْ يَعْبُدوا اللّهَ وَحْدَهُ، فَلَمّا عادَ الْوَزيرُ وَأَخْبَرَها ما حَدَثَ، عَلِمَتْ أَنَّهُ نَبِيٌّ، فَلَوْ لَمْ يَكُنْ نَبِيًّا لَقَبِلَ الْهَدِيَّةَ، وَتَرَكَها وَقَوْمَها يَعْبُدونَ ما يَشاؤونَ، فَقَرَّرَتِ الْمَلِكَةُ تَجْهيزَ مَوْكِبٍ يَقْصِدُ لِقاءَ نِبِيِّ اللّهِ سُلَيْمانَ -عَلَيْهِ السَّلامُ-، وَقَبْلَ أَنْ يَتَحَرَّكَ الْمَوْكِبُ الْمَلَكِيُّ الْمَهيبُ أَرادَتِ الْمَلِكَةُ الِِاطْمِئْنانَ عَلى عَرْشِها، فَأَمَرَتِ الْحُرّاسَ بنِقَْلِهِ إلِى مَكانٍ آمِنٍ.',
                        'has_image' => true,
                    ],
                    [
                        'text' => 'عَلِمَ سُلَيمْانُ -عَلَيْهِ السَّلامُ- بوُِصولِ الْمَوْكبِِ، وَتَذَكَّرَ قَوْلَ الْهُدْهُدِ لَهُ أَوَّلَ مَرَّةٍ: "لَها عَرْشٌ عَظيمٌ". فَقالَ سُلَيْمانُ-عَلَيْهِ السَّلامُ-: "يا أَيُّها الْمَلََأُ، أَيُّكُمْ يَأْتيني بِعَرْشِها قَبْلَ أَنْ يَأْتوني مُسْلِمينَ؟" وَما إِنْ نَظَرَ سُلَيْمانُ -عَلَيْهِ السَّلامُ- إِلى الْيَمينِ قَبْلَ أَنْ يَرْتَدَّ إلَِيْهِ طَرْفُهُ، حَتّى وَجَدَ الْعَرْشَ أَمامَهُ، وَأَرادَ سُلَيْمانُ-عَلَيْهِ السَّلامُ- بذِلكَِ أَنْ يَخْتَبِرَ ذَكاءَ الْمَلِكَةِ، وَهَلْ سَيَكونُ ذلِكَ سَبَبًا في أَنْ تُؤْمِنَ أَمْ تَمْتَنعَِ عَنْ ذلكَِ؟ تَوَقَّفَ الْمَوْكِبُ، وَاسْتَقْبَلَ الْمَلِكَةَ سُلَيْمانُ -عَلَيْهِ السَّلامُ-، فَنَظَرَتْ إِلى الْعَرْشِ، فَقالَ لَها: أَهكَذا عَرْشُكِ؟ فَأَجابَتْ: كَأَنَّهُ هُوَ. فَقالَ سُلَيْمانُ -عَلَيْهِ السَّلامُ-: "بَلْ هُوَ عَرْشُكِ"، فَازْدادَتِ الْمَلِكَةُ يَقينًا بِأَنَّهُ نَبِيٌّ، فَأَسْلَمَتْ وَأَسْلَمَ قَوْمُها مَعَها.',
                        'has_image' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Lesson: زراعة الفطر
     */
    private function getMushroomCultivationLesson(): array
    {
        return [
            'title' => 'زراعة الفطر',
            'category_name_en' => 'Beginner',
            'grade_name' => 'Grade One',
            'grade_level' => 'primary',
            'subject_name_en' => 'Science',
            'is_in_library' => false,
            'training' => [
                'title' => 'Training: زراعة الفطر',
                'title_ar' => 'تدريب: زراعة الفطر',
                'description' => 'Training exercises for the lesson زراعة الفطر',
                'description_ar' => 'تمارين تدريبية لدرس زراعة الفطر',
                'questions' => [
                    // أولاً: أسئلة صح/خطأ (5 أسئلة)
                    [
                        'title' => 'تتطلب زراعة الفطر الاقتصادي درجات حرارة عالية جدًا تصل إلى (180°C) للقضاء على الفيروسات والميكروبات الضارة.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'تعتمد طريقة زراعة الفطر في الأكياس على تقطيع الفطر ومعالجته بالماء الساخن لقتل الجراثيم.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'تُعدُّ الفطريات من النباتات التي تقوم بعملية التمثيل الضوئي لإنتاج غذائها.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'يجب فحص أبواغ الفطريات بالمجهر قبل استخدامها في زراعة الفطر للتأكد من خلوّها من البكتيريا الضارة.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'الفطريات هي كائنات حية دقيقة تتكاثر بالأبواغ.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    // اختر الإجابة (4 أسئلة)
                    [
                        'title' => 'أيٌّ مما يأتي هو المادة الرئيسية التي يتم تبريدها وتعقيمها في المرحلة الأولى من زراعة الفطر الاقتصادي؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'أبواغ الفطر', 'is_correct' => false],
                            ['text' => 'الركام الأخضر', 'is_correct' => false],
                            ['text' => 'تربة التغطية', 'is_correct' => true],
                            ['text' => 'محلول الأمونيا', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'ما هي أفضل نسبة رطوبة يجب توفيرها لضمان نجاح المشروع في المراحل الأولى لزراعة الفطر؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => '50%', 'is_correct' => false],
                            ['text' => '75%', 'is_correct' => false],
                            ['text' => '85%', 'is_correct' => false],
                            ['text' => '95%', 'is_correct' => true],
                        ],
                    ],
                    [
                        'title' => 'تُعتبر الفطريات كائنات:',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'ذاتية التغذية', 'is_correct' => false],
                            ['text' => 'لا تتطلب رطوبة', 'is_correct' => false],
                            ['text' => 'غير ذاتية التغذية', 'is_correct' => true],
                            ['text' => 'تقوم بالتمثيل الضوئي', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'ما هو تركيب الفطريات الذي يُعدُّ الجزء الذي تتكون منه المادة الغذائية ويُستخدم في صناعة الأدوية؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الخيوط الفطرية', 'is_correct' => false],
                            ['text' => 'الأبواغ', 'is_correct' => false],
                            ['text' => 'الخمار', 'is_correct' => true],
                            ['text' => 'الخيوط الجذرية', 'is_correct' => false],
                        ],
                    ],
                    // املأ الفراغ (5 أسئلة)
                    [
                        'title' => 'تعدُّ الفطريات من الكائنات __________، مما يعني أنها لا تستطيع صنع غذائها بنفسها.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'ذاتية التغذية', 'is_correct' => false],
                            ['text' => 'منتجة للغذاء', 'is_correct' => false],
                            ['text' => 'ضوئية التغذية', 'is_correct' => false],
                            ['text' => 'غير ذاتية التغذية', 'is_correct' => true],
                        ],
                    ],
                    [
                        'title' => 'يُطلق على المجموعة التي ينتمي إليها الكائن الحي الذي لا يستطيع إنتاج غذائه بنفسه اسم __________.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'المجموعة الضوئية', 'is_correct' => false],
                            ['text' => 'المجموعة ذاتية التغذية', 'is_correct' => false],
                            ['text' => 'المجموعة المنتِجة', 'is_correct' => false],
                            ['text' => 'المجموعة غير ذاتية التغذية', 'is_correct' => true],
                        ],
                    ],
                    [
                        'title' => 'للقيام بتجربة زراعة الفطر، نحتاج إلى توفير رطوبة بنسبة __________ أو أكثر مع تجنب أشعة الشمس المباشرة.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => '40%', 'is_correct' => false],
                            ['text' => '60%', 'is_correct' => false],
                            ['text' => '85%', 'is_correct' => true],
                            ['text' => '100%', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'يُستخدم __________ في المرحلة الأخيرة من مراحل زراعة الفطر الاقتصادي، ويتم غمره بالماء المغلي لتعقيمه.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الركام الأخضر', 'is_correct' => false],
                            ['text' => 'الأبواغ', 'is_correct' => false],
                            ['text' => 'تربة التغطية', 'is_correct' => true],
                            ['text' => 'الخيوط الجذرية', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'يمكن استنتاج أن الفطريات تحصل على الغذاء عن طريق __________ المواد العضوية الموجودة في الوسط الذي تنمو فيه.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'تخزين', 'is_correct' => false],
                            ['text' => 'إنتاج', 'is_correct' => false],
                            ['text' => 'امتصاص من الهواء', 'is_correct' => false],
                            ['text' => 'تحليل', 'is_correct' => true],
                        ],
                    ],
                ],
            ],
            'book' => [
                'title' => 'زراعة الفطر',
                'language' => 'ar',
                'has_sound' => false,
                'pages' => [
                    [
                        'text' => 'انتشرت زراعة فطر المشروم في الآونة الأخيرة في الأردن، بوصفه من المشروعات الاقتصادية الصغيرة ذات الربحية الأكثر والتكلفة الأقل؛ إذ يمكن تنفيذه في إحدى غرف المنزل. ولضمان نجاح هذا المشروع، لا بد من تجهيز البيئة المناسبة لنمو المشروم التي يمكن شراؤها جاهزة من المؤسسات الزراعية المختصة، كما يَلزم لضمان نموّه توفير المكان المناسب النظيف بدرجة حرارة لا تقل عن (18∘C) ولا تزيد على 30، ونسبة رطوبة لا تزيد على 85%، مع الحرص على عدم وصول أشعة الشمس المباشرة لمكان الزراعة.',
                    ],
                    [
                        'text' => 'ومن طرائق إنتاج المشروم المتبعة ما يسمى طريقة الأكياس، حيث تُعد أسهل الطرائق وأقلها كلفة، حيث توضع طبقة من البيئة الجاهزة في الأكياس، ثم توضع الأبواغ الفطرية وتُضغط برفق، ثم تكرر الخطوة ذاتها مرة أو اثنتين. بعد ذلك يعلّق الكيس جيدًا ويُترك مدة أسبوعين إلى ثلاثة أسابيع حتى يبدأ المشروم بالظهور؛ فيُفتح الكيس عند ذلك من الأعلى ويُترك أسبوعًا، ثم يُفتح الكيس من الجوانب لخروج بعض المشروم منه، ولا بد من الانتباه بشكل مستمر لدرجتي الحرارة والرطوبة المناسبتين له، وعند جمع الفطر يُسوق وتتحقق الفائدة المرجوة من زراعته.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Lesson: إعادة التدوير
     */
    private function getRecyclingLesson(): array
    {
        return [
            'title' => 'إعادة التدوير',
            'category_name_en' => 'Beginner',
            'grade_name' => 'Grade One',
            'grade_level' => 'primary',
            'subject_name_en' => 'Science',
            'is_in_library' => false,
            'training' => [
                'title' => 'Training: إعادة التدوير',
                'title_ar' => 'تدريب: إعادة التدوير',
                'description' => 'Training exercises for the lesson إعادة التدوير',
                'description_ar' => 'تمارين تدريبية لدرس إعادة التدوير',
                'questions' => [
                    // أولًا: أسئلة صح/خطأ (5 أسئلة)
                    [
                        'title' => 'تمثل المخلفات مشكلة بيئية تؤثر في صحة الإنسان.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'تدوير المخلفات يعني التخلص منها نهائيًّا دون إعادة استخدامها.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'من المواد التي يمكن تدويرها الورق والبلاستيك والمعادن.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'يساعد التدوير في المحافظة على موارد الطاقة للأجيال القادمة.',
                        'type' => 'true_false',
                        'is_correct' => true,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    [
                        'title' => 'لا يوفر التدوير أي فرص عمل للأشخاص.',
                        'type' => 'true_false',
                        'is_correct' => false,
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                    ],
                    // أيٌّ من التالي (3 أسئلة)
                    [
                        'title' => 'أيٌّ من التالي يُعد من المخلفات التي يمكن تدويرها؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'التراب', 'is_correct' => false],
                            ['text' => 'الكرتون', 'is_correct' => true],
                            ['text' => 'الماء', 'is_correct' => false],
                            ['text' => 'الهواء', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'من فوائد تدوير المخلفات:',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'زيادة التلوث', 'is_correct' => false],
                            ['text' => 'استهلاك الموارد الطبيعية', 'is_correct' => false],
                            ['text' => 'توفير فرص عمل', 'is_correct' => true],
                            ['text' => 'تقليل النظافة', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'تدوير المخلفات يساعد في تجنّب تلوث:',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الماء والهواء', 'is_correct' => true],
                            ['text' => 'الجبال', 'is_correct' => false],
                            ['text' => 'الصحارى', 'is_correct' => false],
                            ['text' => 'الفضاء', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'لماذا يجب النظر إلى المخلفات على أنها مورد؟',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'لأنها ليس لها قيمة', 'is_correct' => false],
                            ['text' => 'لأنها تزيد عدد السكان', 'is_correct' => false],
                            ['text' => 'لأنها يمكن الاستفادة منها', 'is_correct' => true],
                            ['text' => 'لأنها تستهلك الطاقة', 'is_correct' => false],
                        ],
                    ],
                    // اختر الإجابة الصحيحة (5 أسئلة)
                    [
                        'title' => 'يطرح الإنسان كميات كبيرة من ______ يوميًّا.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الماء', 'is_correct' => false],
                            ['text' => 'المخلفات', 'is_correct' => true],
                            ['text' => 'التربة', 'is_correct' => false],
                            ['text' => 'الهواء', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'من المخلفات التي يمكن تدويرها: الورق، والزجاج، و_______.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الرمل', 'is_correct' => false],
                            ['text' => 'الأحجار', 'is_correct' => false],
                            ['text' => 'البلاستيك', 'is_correct' => true],
                            ['text' => 'الملابس', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'من فوائد التدوير: المحافظة على ______ الطبيعية.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الألعاب', 'is_correct' => false],
                            ['text' => 'الموارد', 'is_correct' => true],
                            ['text' => 'الحيوانات فقط', 'is_correct' => false],
                            ['text' => 'المنازل', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'يساعد التدوير في تجنُّب تلوث _______.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الهواء والماء', 'is_correct' => true],
                            ['text' => 'الأشجار فقط', 'is_correct' => false],
                            ['text' => 'المباني', 'is_correct' => false],
                            ['text' => 'الكتب', 'is_correct' => false],
                        ],
                    ],
                    [
                        'title' => 'أفكّر في عمل مشروع صغير لتدوير ______ المنزلية.',
                        'type' => 'choice',
                        'xp' => 10,
                        'coins' => 5,
                        'marks' => 1,
                        'language' => 'ar',
                        'options' => [
                            ['text' => 'الملابس', 'is_correct' => false],
                            ['text' => 'الألعاب', 'is_correct' => false],
                            ['text' => 'المخلفات', 'is_correct' => true],
                            ['text' => 'الأدوات', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'book' => [
                'title' => 'إعادة التدوير',
                'language' => 'ar',
                'has_sound' => false,
                'pages' => [
                    [
                        'text' => 'يطرح الإنسان كميات كبيرة من المخلفات يوميًّا، ما يمثّل مشكلة بيئية مستمرة، تؤثر في صحة الإنسان نفسه، فضلًا عن تأثيرها سلبيًّا في البيئة. يُعد تدوير المخلفات إحدى الطرائق الفاعلة لتجنّب أضرارها، وللمحافظة على بيئتنا نظيفة.<br><br>يُقصد بالتدوير استخدام المخلفات اليومية - بوصفها من المواد الخام - في صناعة منتجات جديدة. ومن المخلفات التي يمكن تدويرها: مخلفات الورق، والكرتون، والزجاج، والبلاستيك، والمعادن، وبقايا الكائنات الحية، وبقايا الطعام.',
                    ],
                    [
                        'text' => 'للتدوير فوائد كثيرة، منها: المحافظة على الموارد الطبيعية وموارد الطاقة وتوفيرها للأجيال القادمة، وتجنُّب تلوث الماء والهواء، والمحافظة على الكائنات الحية ومواطنها، وتوفير فرص عمل لكثير من الأشخاص. لذا، ينبغي لنا النظر إلى هذه المخلفات بوصفها موردًا يمكن استغلاله، وكذلك تهيئة السبل التي تساعد الأفراد والمؤسسات على تدوير المخلفات.<br><br>مشروع: أفكّر في عمل مشروع صغير لتدوير المخلفات المنزلية.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Create a lesson with its training, questions, and book
     */
    private function createLesson(array $lessonData): void
    {
        // Get category
        $category = LessonCategory::where('name_en', $lessonData['category_name_en'])->first();
        if (!$category) {
            $this->command->warn("⚠️  Category not found: {$lessonData['category_name_en']}");
            return;
        }

        // Get grade
        $grade = Grade::where('name', $lessonData['grade_name'])
            ->where('level', $lessonData['grade_level'] ?? 'primary')
            ->first();
        if (!$grade) {
            $this->command->warn("⚠️  Grade not found: {$lessonData['grade_name']} (level: " . ($lessonData['grade_level'] ?? 'primary') . ")");
            return;
        }

        // Get subject
        $subject = Subject::where('name_en', $lessonData['subject_name_en'])->first();
        if (!$subject) {
            $this->command->warn("⚠️  Subject not found: {$lessonData['subject_name_en']}");
            return;
        }

        // Create training
        $training = $this->createTraining($lessonData['training'], $subject->id);
        if (!$training) {
            $this->command->warn("⚠️  Failed to create training for lesson: {$lessonData['title']}");
            return;
        }

        // Create book
        $book = $this->createBook($lessonData['book'], $subject->id, $lessonData['is_in_library'] ?? false);
        if (!$book) {
            $this->command->warn("⚠️  Failed to create book for lesson: {$lessonData['title']}");
            return;
        }

        // Create lesson
        $lesson = Lesson::create([
            'title' => $lessonData['title'],
            'category_id' => $category->id,
            'grade_id' => $grade->id,
            'subject_id' => $subject->id,
            'train_id' => $training->id,
        ]);

        // Attach book to lesson
        $lesson->books()->attach($book->id);

        // Attach video to lesson if it exists (for recycling lesson)
        if ($lessonData['title'] === 'إعادة التدوير') {
            // Find video that is NOT linked to any training (videos in lessons should not have related_training_id)
            // Take the first video that is not linked to training (for lesson)
            $video = Video::where('title_ar', 'إعادة التدوير')
                ->whereNull('related_training_id')
                ->orderBy('id', 'asc')
                ->first();

            if ($video) {
                // Attach video to lesson via many-to-many relationship
                $lesson->videos()->attach($video->id);

                $this->command->info("   🎥 Attached video: {$video->title_ar} to lesson (ID: {$video->id})");
            } else {
                $this->command->warn("   ⚠️  Video 'إعادة التدوير' (without related_training_id) not found. Make sure VideoSeeder runs before LessonSeeder.");
            }
        }

        $this->command->info("✅ Created lesson: {$lesson->title}");
    }

    /**
     * Create training with questions
     */
    private function createTraining(array $trainingData, int $subjectId): ?ExamTraining
    {
        $training = ExamTraining::create([
            'title' => $trainingData['title'],
            'title_ar' => $trainingData['title_ar'],
            'description' => $trainingData['description'],
            'description_ar' => $trainingData['description_ar'],
            'type' => 'training',
            'duration' => null,
            'created_by' => 1, // Assuming teacher ID 1 exists
            'subject_id' => $subjectId,
            'group_id' => null,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => null,
        ]);

        $this->command->info("   📝 Created training: {$training->title_ar}");

        // Create questions
        $questionCount = 0;
        foreach ($trainingData['questions'] as $questionData) {
            $this->createQuestion($training->id, $questionData);
            $questionCount++;
        }

        $this->command->info("      ✅ Created {$questionCount} questions");

        return $training;
    }

    /**
     * Create a single question with its options
     */
    private function createQuestion(int $examTrainingId, array $questionData): void
    {
        $type = $questionData['type'];
        $language = $questionData['language'] ?? 'en';

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
                $this->createTrueFalseOption($question->id, $questionData['is_correct'] ?? true, $language);
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
     * Create book with pages
     */
    private function createBook(array $bookData, int $subjectId, bool $isInLibrary = false): ?Book
    {
        $pages = $bookData['pages'] ?? [];

        // Generate folder name from title
        $folderName = $this->titleToSlug($bookData['title']);

        // Create book
        $book = Book::create([
            'title' => $bookData['title'],
            'is_in_library' => $isInLibrary,
            'language' => $bookData['language'],
            'has_sound' => $bookData['has_sound'],
            'xp' => 100,
            'coins' => 40,
            'marks' => 75,
            'subject_id' => $subjectId,
            'level_id' => null,
            'cover' => "books/{$folderName}/cover.svg",
            'thumbnail' => "books/{$folderName}/thumbnail.jpg",
        ]);

        $this->command->info("   📖 Created book: {$book->title}");

        // Create folder structure
        $this->createBookFolders($folderName, count($pages));

        // Create pages
        foreach ($pages as $index => $pageData) {
            $pageNumber = $index + 1;
            $hasImage = $pageData['has_image'] ?? true; // Default to true if not specified

            Page::create([
                'book_id' => $book->id,
                'text' => $pageData['text'],
                'image' => $hasImage ? "books/{$folderName}/pages/page_{$pageNumber}/image.png" : null,
                'mp3' => $book->has_sound ? "books/{$folderName}/pages/page_{$pageNumber}/audio.mp3" : null,
                'is_text_to_speech' => !$book->has_sound,
            ]);
        }

        $this->command->info("      ✅ Created " . count($pages) . " pages");

        return $book;
    }

    /**
     * Convert title to English slug for folder naming
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

    /**
     * Create folder structure for a book
     */
    private function createBookFolders(string $folderName, int $numberOfPages): void
    {
        $basePath = storage_path("app/public/books/{$folderName}");

        // Create main book folder
        File::makeDirectory($basePath, 0755, true, true);

        // Create pages folder and subfolders for each page
        for ($i = 1; $i <= $numberOfPages; $i++) {
            File::makeDirectory("{$basePath}/pages/page_{$i}", 0755, true, true);
        }
    }
}
