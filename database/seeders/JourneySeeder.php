<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Infrastructure\Models\JourneyLevel;
use App\Infrastructure\Models\JourneyStage;
use App\Infrastructure\Models\StageContent;
use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\Page;
use App\Infrastructure\Models\ExamTraining;
use App\Infrastructure\Models\Question;
use App\Infrastructure\Models\QuestionOption;
use App\Infrastructure\Models\QuestionOptionPair;
use App\Infrastructure\Models\Video;
use Carbon\Carbon;

class JourneySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting Journey Seeding...');

        $levels = $this->createLevels();
        $this->createFirstLevelStage($levels['beginner']);
        $this->createEmptyStagesForLevel($levels['skillChallenge']);
        $this->createEmptyStagesForLevel($levels['knowledgeLegend']);

        $this->command->info('✅ Journey seeded successfully!');
    }

    private function createLevels(): array
    {
        $levels = [];

        $levels['beginner'] = JourneyLevel::create([
            'name_ar' => 'مستوى البداية',
            'name_en' => 'Beginner Level',
            'order' => 1,
        ]);

        $levels['skillChallenge'] = JourneyLevel::create([
            'name_ar' => 'تحدّي المهارة',
            'name_en' => 'Skill Challenge',
            'order' => 2,
        ]);

        $levels['knowledgeLegend'] = JourneyLevel::create([
            'name_ar' => 'أسطورة المعرفة',
            'name_en' => 'Knowledge Legend',
            'order' => 3,
        ]);

        $this->command->info('✅ Created 3 journey levels');

        return $levels;
    }

    private function createFirstLevelStage(JourneyLevel $level): void
    {
        $this->createFirstStage($level);
        $this->createSecondStage($level);
        $this->createThirdStage($level);
        $this->createFourthStage($level);
        $this->createFifthStage($level);

        $this->command->info('✅ Created first level stages and contents');
    }

    private function createEmptyStagesForLevel(JourneyLevel $level): void
    {
        $types = ['book', 'examTraining', 'video', 'book', 'examTraining'];

        for ($i = 1; $i <= 5; $i++) {
            JourneyStage::create([
                'level_id' => $level->id,
                'type' => $types[$i - 1],
                'order' => $i,
            ]);
        }

        $this->command->info("✅ Created 5 empty stages for level: {$level->name_en}");
    }

    private function createFirstStage(JourneyLevel $level): void
    {
        $stage = JourneyStage::create([
            'level_id' => $level->id,
            'type' => 'book',
            'order' => 1,
        ]);

        $this->addBookWhyAmISquare($stage);
        // $this->addJourneyTraining($stage);
    }

    private function createSecondStage(JourneyLevel $level): void
    {
        $stage = JourneyStage::create([
            'level_id' => $level->id,
            'type' => 'examTraining',
            'order' => 2,
        ]);

        $this->addSecondStageTraining1($stage);
        $this->addSecondStageTraining2($stage);
        $this->addSecondStageTraining3($stage);
    }

    private function createThirdStage(JourneyLevel $level): void
    {
        $stage = JourneyStage::create([
            'level_id' => $level->id,
            'type' => 'video',
            'order' => 3,
        ]);

        $this->addVideoRecycling($stage);
        $this->addThirdStageTraining1($stage);
        $this->addThirdStageTraining2($stage);
    }

    private function createFourthStage(JourneyLevel $level): void
    {
        $stage = JourneyStage::create([
            'level_id' => $level->id,
            'type' => 'examTraining',
            'order' => 4,
        ]);

        $this->addFourthStageTraining1($stage);
        $this->addFourthStageTraining2($stage);
    }

    private function createFifthStage(JourneyLevel $level): void
    {
        $stage = JourneyStage::create([
            'level_id' => $level->id,
            'type' => 'examTraining',
            'order' => 5,
        ]);

        $this->addFifthStageTraining1($stage);
    }

    private function addBookWhyAmISquare(JourneyStage $stage): void
    {
        $book = $this->createBookWhyAmISquare();
        $this->createTrainingWhyAmISquare($book->id);

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'book',
            'content_id' => $book->id,
        ]);
    }

    private function addJourneyTraining(JourneyStage $stage): void
    {
        $training = $this->createJourneyTraining();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function addSecondStageTraining1(JourneyStage $stage): void
    {
        $training = $this->createSecondStageTraining1();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function addSecondStageTraining2(JourneyStage $stage): void
    {
        $training = $this->createSecondStageTraining2();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function addSecondStageTraining3(JourneyStage $stage): void
    {
        $training = $this->createSecondStageTraining3();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function createBookWhyAmISquare(): Book
    {
        $bookData = [
            'title' => 'لماذا انا مربع',
            'is_in_library' => false,
            'language' => 'ar',
            'has_sound' => true,
            'xp' => 100,
            'coins' => 50,
            'marks' => 75,
            'subject_id' => null,
            'level_id' => 2,
            'pages' => [
                'في زمانِ غَيْر هذا الزمان وَفِي عَالَم لَمْ تَسْمَحْ أَوْ تَرَ مِثْلَهُ مِنْ قَبْلُ، عَاشَ أَبْطَالُ قِصَّتِنا.',
                'تَزَوَّجَ بابا - دائرة - مِنْ ماما - دائرة - وَعَاشَا فِي سَعَادَةٍ فِي الْبَيْتِ الدَائِرِي',
                'بَعْدَ فَتْرَةٍ وَحِيزَةٍ أَصْبَحَ بَطْنُها دَائِرِيًّا.',
                'وَلِتَلِدَ، ذَهَبَتْ ماما - دائِرَةُ - إلى الطبيب الدَّائِرِي في الْمُسْتَشْفَى الدَائِرِيِّ، حَضَرَتِ الْمُمَرِّضَاتُ الدائريات إلى غُرْفَةِ الْوِلادَةِ الدَّائِرية',
                'بَيْنَها كان بابا - دائِرَةُ - يَمْشِي بِفَلَقِ بِانْتِظارِ مَولُودِهِ أَمامَ الباب الدَائِرِيِّ، هَذَا مِنْ رَوْعِهِ صَوْتُ بُكاءٍ يُعْلِنُ وَلَادَةَ ابْنِهِ الدَائِرِي الْحَظَةً وَاحِدَةً فَقَطْ ... عُدْرًا) ... ابْنِهِ الْمُرَبِّع!',
                'هذا الْمُرَبَّعُ هُوَ بَطَلُ قِصَّتِنا كَبُرَ الْوَلَدُ الْمُرَبَّحُ وَأَحَبَّهُ أَبوهُ وَأُمُّهُ كَمَا لَمْ يُحِبًا أَحَدًا مِنْ قَبْلُ. لَكِنَّ الْوَلَدَ الْمُرَبَّعَ بَدَأَ يَكْبُرُ، وَفِي كُلِّ يَوْمٍ كَانَ يُلاحِظُ النَّظَرَاتِ الدَائِرِيَّةُ الْغَرِيبَةَ لَهُ أَيْنَهَا ذَهَبَ، فَهُوَ مُخْتَلِفٌ?',
                'بَدَأَ صَدِيقُنَا الْمُرَبَّحُ بِالذَّهَابِ إِلَى الْمَدْرَسَةِ الدَائِرِيَّةِ، وَفِي كُلِّ يَومٍ كَانَ يَعودُ باليَا شَاكِيًا لِأُمِّهِ وَأَبِيهِ. فَقَدْ كَانَتْ بَاقِي الدَّوائِرِ تَسْخَرُ مِنْ شَكْلِهِ الغريب. كان والِداهُ يُشجّعانِهِ وَيُذَكِّرانِهِ أَنَّهُ مُحَيَّةٌ، وَأَنَّ تَمَيَّزَهُ هذا سَبَبٌ في حُبِّهِمَا الْمُمَيَّزِ لَهُ، إِلا أَنَّ ذلِكَ لَم يُخَفِّفْ مِنْ سُخْرِيَةِ بَاقِي الدَّوَائِرِ مِنْهُ، حَتَّى جَاءَ ذلِكَ الْيَوْمُ الَّذي قَلَبَ الْمَوَازِينَ فِي عَالَمِ الدَّوائِرِ',
                'في ذلِكَ الْيَومِ نَظَّمَتِ الْمَدْرَسَةُ رِحْلَةً إِلى أَحَدِ الْبَرَاكِينِ، ذَهَبَ الْأَوْلادُ كُلُّهُمْ بِمَنْ فِيهِمْ صَدِيقُنا الْمُرَبَّعُ إِلى تِلْكَ الرَّحْلَةِ.',
                'وَبَيْنَمَا كَانَ الْأَوْلادُ يَلْعَبُونَ بِسَعَادَةٍ حَصَلَ زِلْزَالٌ رَهِيبٌ، وَشُقَّتِ الْأَرْضُ، وَبَدَأَ الطَّلَابُ الدَائِرِيَونَ وَيُعَلِّمَتُهُمُ الدَائِرِيَّةُ بِالتَّدَحْرُج، بَيْنَما يَصْرُخُونَ طَالِبينَ النَجْدَةَ إِلَّا أَنَّ أَحَدًا لَمْ يَجْرُهُ عَلَى الْاِقْتِرابِ، فَالدَّوائِرُ لَا تَسْتَطِيعُ إِلَّا أَنْ تَتَدَخْرَجَ.',
                'سَمِعَ صَدِيقُنَا الْمُرَبَّحُ صُرَخَاتِ زُمَلَائِهِ وَمُعَلِّمَتِهِ، فَتَحَرَّكَ بِشَجَاعَةِ لِيَسُدَّ الطَّرِيقَ إِلَى الْهَاوِيَةِ. وَلكَوْنِهِ مُرَبَّعًا فَهُوَ أَكْثَرُ ثَبَاتًا عَلَى الْأَرْضِ، نَجَحَ صَدِيقُنا فِي إِيقَافِ زُمَلَائِهِ مِنَ التَّدَحْرُجِ حَتَّى وَصَلَتِ الْمُسَاعَدَةُ',
                'بعْدَ أَنْ وَصَلَ الْجَمِيعُ إِلَى بَرِّ الْأَمَانِ، تَدَحْرَجُوا إِلَى الْمُرَبَّعِ وَحَمَلُوهُ عَلَى الْأَكْتَافِ وَهَتَفُوا لَهُ',
                'مُنْذُ ذَلِكَ الْيَوْمِ فَهِمَ الْجَمِيعُ قِيمَةَ الْمُرَبَّعِ فِي عَالَمِ الدَّوَائِرِ وَقَدَّرُوهَا ... وَلَمْ يَعُدْ أَحَدٌ يَسْخَرُ مِنْهُ ... فَلِكُلٍّ مِنَّا مَا يُمَيِّزُهُ فِي هَذِهِ الْحَيَاةِ',
            ],
        ];

        return $this->createBook($bookData);
    }

    private function createBook(array $bookData): Book
    {
        $pages = $bookData['pages'] ?? [];
        unset($bookData['pages']);

        $folderName = $this->titleToSlug($bookData['title']);

        $bookData['cover'] = "books/{$folderName}/cover.svg";
        $bookData['thumbnail'] = "books/{$folderName}/thumbnail.jpg";

        $book = Book::create($bookData);

        $this->createBookFolders($folderName, count($pages));

        foreach ($pages as $index => $pageText) {
            $pageNumber = $index + 1;
            Page::create([
                'book_id' => $book->id,
                'text' => $pageText,
                'image' => "books/{$folderName}/pages/page_{$pageNumber}/image.png",
                'mp3' => $book->has_sound ? "books/{$folderName}/pages/page_{$pageNumber}/audio.mp3" : null,
                'is_text_to_speech' => !$book->has_sound,
            ]);
        }

        return $book;
    }

    private function createTrainingWhyAmISquare(int $bookId): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Training: Why Am I Square',
            'title_ar' => 'تدريب كتاب لماذا انا مربع',
            'description' => 'Training exercises for the book Why Am I Square',
            'description_ar' => 'تمارين تدريبية لكتاب لماذا انا مربع',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        Book::where('id', $bookId)->update(['related_training_id' => $training->id]);

        $this->createWhyAmISquareQuestions($training->id);

        return $training;
    }

    private function createJourneyTraining(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Questions Training',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'First journey training exercise',
            'description_ar' => 'التمرين الأول',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createJourneyQuestions($training->id);

        return $training;
    }

    private function createSecondStageTraining1(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training 1',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Second stage first training',
            'description_ar' => 'التمرين الأول',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createSecondStageTraining1Questions($training->id);

        return $training;
    }

    private function createSecondStageTraining2(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training 2',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Second stage second training',
            'description_ar' => 'التمرين الثاني',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createSecondStageTraining2Questions($training->id);

        return $training;
    }

    private function createSecondStageTraining3(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training 3',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Second stage third training - General Culture',
            'description_ar' => 'ثقافة عامة',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createSecondStageTraining3Questions($training->id);

        return $training;
    }

    private function createWhyAmISquareQuestions(int $trainingId): void
    {
        $choiceQuestions = [
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
        ];

        $arrangeQuestions = [
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
        ];

        $trueFalseQuestions = [
            [
                'title' => 'وُلد الطفل على شكل مربع في عالم كله دوائر.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'استخدم المربّع شجاعته ليساعد زملاءه أثناء الزلزال.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'كانت الدوائر في المدرسة تُشجّع المربّع دائمًا وتُصفّق له.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false,
            ],
        ];

        // Merge and shuffle to mix question types
        $questions = array_merge($choiceQuestions, $arrangeQuestions, $trueFalseQuestions);
        shuffle($questions);

        $this->createQuestions($trainingId, $questions);
    }

    private function createJourneyQuestions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'الهمزة المتطرفة تكتب حسب حركة ما قبلها.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'التنوين لا يُكتب في نهاية الكلمة.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false,
            ],
            [
                'title' => 'الفعل الماضي يدل على حدث وقع وانتهى.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'الجمع المؤنث السالم ينتهي بـ(ات).',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            // Commented out: الجملة الفعلية تبدأ دائماً بفاعل.
        ];

        $choiceQuestions = [
            [
                'title' => 'فعل الأمر من كلمة (كتب) هو:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'يكتب', 'is_correct' => false],
                    ['text' => 'اكتبْ', 'is_correct' => true],
                    ['text' => 'كاتب', 'is_correct' => false],
                    ['text' => 'مكتوب', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'المبتدأ مرفوع دائمًا وعلامة رفعه ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الفتحة', 'is_correct' => false],
                    ['text' => 'السكون', 'is_correct' => false],
                    ['text' => 'الكسرة', 'is_correct' => false],
                    ['text' => 'الضمة', 'is_correct' => true],
                ],
            ],
            [
                'title' => 'مفرد كلمة (مدارس) هو:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'مدرسة', 'is_correct' => true],
                    ['text' => 'دارس', 'is_correct' => false],
                    ['text' => 'درس', 'is_correct' => false],
                    ['text' => 'مدرس', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'فعل الماضي من (يلعبُ) هو ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'يلعب', 'is_correct' => false],
                    ['text' => 'لاعب', 'is_correct' => false],
                    ['text' => 'لعبَ', 'is_correct' => true],
                    ['text' => 'لعبٌ', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'كلمة (الطائرُ جميلٌ) نوع الجملة:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'فعلية', 'is_correct' => false],
                    ['text' => 'اسمية', 'is_correct' => true],
                    ['text' => 'استفهامية', 'is_correct' => false],
                    ['text' => 'أمرية', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'من أدوات النفي:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'هل', 'is_correct' => false],
                    ['text' => 'ما', 'is_correct' => true],
                    ['text' => 'قد', 'is_correct' => false],
                    ['text' => 'لمّا', 'is_correct' => false],
                ],
            ],
            // Commented out: الكلمة التي تحتوي على همزة متوسطة
            // Commented out: كلمة (حديقة) نوعها
        ];

        $connectQuestions = [
            [
                'title' => 'صِل كل كلمة بنوعها:',
                'type' => 'connect',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'pairs' => [
                    ['left' => 'كتبَ', 'right' => 'فعل ماضٍ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'يكتبُ', 'right' => 'فعل مضارع', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'كتاب', 'right' => 'اسم', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'هل', 'right' => 'أداة استفهام', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'لم', 'right' => 'أداة جزم', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
            [
                'title' => 'صِل المبتدأ بالخبر المناسب:',
                'type' => 'connect',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'pairs' => [
                    ['left' => 'السماءُ', 'right' => 'صافيةٌ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الطالبُ', 'right' => 'مجتهدٌ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'المدرسةُ', 'right' => 'قريبةٌ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الوردةُ', 'right' => 'جميلةٌ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الكتابُ', 'right' => 'مفيدٌ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
        ];

        // Take 4 true/false, 6 choice, and 2 connect = 12 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 4),
            array_slice($choiceQuestions, 0, 6),
            $connectQuestions
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function createSecondStageTraining1Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'القمر أكبر من الشمس.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false,
            ],
            [
                'title' => 'جسم الإنسان يحتوي على قلب واحد فقط.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'النباتات تحتاج إلى الضوء والماء لتنمو.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            // Commented out: أطول نهر في العالم هو نهر الأمازون.
            // Commented out: البطريق يعيش في الصحراء.
        ];

        $choiceQuestions = [
            [
                'title' => 'عاصمة دولة مصر هي ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الرياض', 'is_correct' => false],
                    ['text' => 'القاهرة', 'is_correct' => true],
                    ['text' => 'الخرطوم', 'is_correct' => false],
                    ['text' => 'دمشق', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الحيوان الذي يُلقَّب بـ (ملك الغابة) هو ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'النمر', 'is_correct' => false],
                    ['text' => 'الأسد', 'is_correct' => true],
                    ['text' => 'الفهد', 'is_correct' => false],
                    ['text' => 'الذئب', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الجهاز المسؤول عن التنفس في جسم الإنسان هو ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'القلب', 'is_correct' => false],
                    ['text' => 'المعدة', 'is_correct' => false],
                    ['text' => 'الرئتان', 'is_correct' => true],
                    ['text' => 'الكبد', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'أكبر قارات العالم مساحة هي ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'أوروبا', 'is_correct' => false],
                    ['text' => 'آسيا', 'is_correct' => true],
                    ['text' => 'إفريقيا', 'is_correct' => false],
                    ['text' => 'أمريكا الجنوبية', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الغاز الذي نتنفسه ويفيد الجسم هو غاز ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الهيدروجين', 'is_correct' => false],
                    ['text' => 'ثاني أكسيد الكربون', 'is_correct' => false],
                    ['text' => 'الأكسجين', 'is_correct' => true],
                    ['text' => 'النيتروجين', 'is_correct' => false],
                ],
            ],
            // Commented out: أكبر محيطات العالم هو
            // Commented out: أين يعيش الجمل؟
            // Commented out: الحيوان الذي يبيض هو
        ];

        $arrangeQuestions = [
            [
                'title' => 'رتّب الكلمات لتكوين جملة صحيحة: الصحراء – الجمل – دائمًا – الكبيرة – يعيش – في – بسرعة',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'يعيش', 'order' => 1],
                    ['text' => 'الجمل', 'order' => 2],
                    ['text' => 'في', 'order' => 3],
                    ['text' => 'الصحراء', 'order' => 4],
                    ['text' => 'الكبيرة', 'order' => 5],
                ],
            ],
            [
                'title' => 'رتّب الكلمات لتكوين جملة صحيحة: الشمس – الجميلة – كل يوم – بسرعة – حول – تدور – الأرض',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'تدور', 'order' => 1],
                    ['text' => 'الأرض', 'order' => 2],
                    ['text' => 'حول', 'order' => 3],
                    ['text' => 'الشمس', 'order' => 4],
                    ['text' => 'الجميلة', 'order' => 5],
                    ['text' => 'كل', 'order' => 6],
                    ['text' => 'يوم', 'order' => 7],
                ],
            ],
            [
                'title' => 'رتّب الكلمات لتكوين جملة صحيحة: محيط – ماء – الهادي – الأكبر – الأرض – في – الكرة',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'محيط', 'order' => 1],
                    ['text' => 'الهادي', 'order' => 2],
                    ['text' => 'الأكبر', 'order' => 3],
                    ['text' => 'محيط', 'order' => 4],
                    ['text' => 'في', 'order' => 5],
                    ['text' => 'الكرة', 'order' => 6],
                    ['text' => 'الأرض', 'order' => 7],
                ],
            ],
        ];

        $connectQuestions = [
            [
                'title' => 'صِلْ بين الاختراع والمخترع:',
                'type' => 'connect',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'pairs' => [
                    ['left' => 'الهاتف', 'right' => 'بيل', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'المصباح', 'right' => 'أديسون', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الجاذبية', 'right' => 'نيوتن', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
        ];

        // Take 3 true/false, 5 choice, 3 arrange, and 1 connect = 12 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 3),
            array_slice($choiceQuestions, 0, 5),
            $arrangeQuestions,
            $connectQuestions
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function createSecondStageTraining2Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'كلمة (مُهَيْمِن) معناها: المسيطر الحافظ.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'عدد ركعات صلاة الفجر أربع ركعات.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => false,
            ],
            [
                'title' => '"لَا تَأْخُذُهُ سِنَةٌ وَلَا نَوْمٌ…" يدل على كمال قدرة الله وعظمته.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'من مظاهر قدرة الله تعالى: خلق الكون، وإحياء الموتى، وإنزال المطر.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'وُلد الرسول محمد ﷺ في مدينة مكة.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            // Commented out: الصحابي الذي رافق النبي ﷺ في الهجرة هو: عبد الله بن مسعود.
            // Commented out: رفضت بلقيس دعوة سليمان عليه السلام.
        ];

        $choiceQuestions = [
            [
                'title' => 'مدة دعوة نوح عليه السلام لقومه:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => '900 سنة', 'is_correct' => false],
                    ['text' => '950 سنة', 'is_correct' => true],
                    ['text' => '800 سنة', 'is_correct' => false],
                    ['text' => '1000 سنة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ما اسم أبي النبي محمد ﷺ؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'عبد المطلب', 'is_correct' => false],
                    ['text' => 'عبد الله', 'is_correct' => true],
                    ['text' => 'أبو طالب', 'is_correct' => false],
                    ['text' => 'الحارث', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'إذا جاء أحد حروف الإدغام بعد النون الساكنة أو التنوين ندمج النون مع الحرف التالي، ويسمى هذا:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الإظهار', 'is_correct' => false],
                    ['text' => 'الإقلاب', 'is_correct' => false],
                    ['text' => 'الإدغام', 'is_correct' => true],
                    ['text' => 'الإخفاء', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'لقب ملك الحبشة هو:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'المعز', 'is_correct' => false],
                    ['text' => 'النجاشي', 'is_correct' => true],
                    ['text' => 'المأمون', 'is_correct' => false],
                    ['text' => 'الفرعون', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'السورة التي تحوي آية الكرسي هي:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الفاتحة', 'is_correct' => false],
                    ['text' => 'البقرة', 'is_correct' => true],
                    ['text' => 'المائدة', 'is_correct' => false],
                    ['text' => 'الإسراء', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'نزل القرآن الكريم في شهر _____.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'شوال', 'is_correct' => false],
                    ['text' => 'محرم', 'is_correct' => false],
                    ['text' => 'صفر', 'is_correct' => false],
                    ['text' => 'رمضان', 'is_correct' => true],
                ],
            ],
            [
                'title' => 'قِبلة المسلمين هي اتجاه _____.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'المدينة', 'is_correct' => false],
                    ['text' => 'القدس', 'is_correct' => false],
                    ['text' => 'الكعبة', 'is_correct' => true],
                    ['text' => 'المسجد النبوي', 'is_correct' => false],
                ],
            ],
            // Commented out: الصلاة المفروضة عددها _____ صلوات يوميًا.
        ];

        // Take 5 true/false and 7 choice = 12 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 5),
            array_slice($choiceQuestions, 0, 7)
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function createSecondStageTraining3Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'مكة المكرمة تقع في المملكة العربية السعودية.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'الحوت أكبر حيوان يعيش على الأرض.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            [
                'title' => 'عدد أيام السنة 365 يومًا.',
                'type' => 'true_false',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'is_correct' => true,
            ],
            // Commented out: الكهرباء تُصنع من الشمس فقط.
            // Commented out: الهواء ليس له وزن.
        ];

        $choiceQuestions = [
            [
                'title' => 'اللون الناتج عن مزج الأحمر والأصفر هو ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'البنفسجي', 'is_correct' => false],
                    ['text' => 'الأخضر', 'is_correct' => false],
                    ['text' => 'البرتقالي', 'is_correct' => true],
                    ['text' => 'البني', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الكوكب الذي يُعرف باسم (الكوكب الأحمر) هو ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'المشتري', 'is_correct' => false],
                    ['text' => 'زحل', 'is_correct' => false],
                    ['text' => 'المريخ', 'is_correct' => true],
                    ['text' => 'عطارد', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'عدد ساعات اليوم الواحد ____________ ساعة.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => '12', 'is_correct' => false],
                    ['text' => '24', 'is_correct' => true],
                    ['text' => '20', 'is_correct' => false],
                    ['text' => '30', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الحيوان الذي ينام طوال الشتاء تقريبًا هو ____________.',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الثعلب', 'is_correct' => false],
                    ['text' => 'الدب', 'is_correct' => true],
                    ['text' => 'الأرنب', 'is_correct' => false],
                    ['text' => 'الزرافة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'كم عدد القارات في العالم؟',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => '5', 'is_correct' => false],
                    ['text' => '6', 'is_correct' => false],
                    ['text' => '7', 'is_correct' => true],
                    ['text' => '4', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الدولة المشهورة بصناعة السيارات (تويوتا) هي:',
                'type' => 'choice',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'الصين', 'is_correct' => false],
                    ['text' => 'اليابان', 'is_correct' => true],
                    ['text' => 'الولايات المتحدة', 'is_correct' => false],
                    ['text' => 'كوريا الجنوبية', 'is_correct' => false],
                ],
            ],
            // Commented out: الحيوان الذي يُعرف بذكائه الكبير هو
            // Commented out: من اخترع المصباح الكهربائي؟
            // Commented out: الوحدة التي نستخدمها لقياس الوزن هي
        ];

        $arrangeQuestions = [
            [
                'title' => 'رتّب الكلمات لتكوين جملة صحيحة: القطب – الجنوبي – في – البطاريق – الحيوان – تعيش – حديقة',
                'type' => 'arrange',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'options' => [
                    ['text' => 'تعيش', 'order' => 1],
                    ['text' => 'البطاريق', 'order' => 2],
                    ['text' => 'في', 'order' => 3],
                    ['text' => 'القطب', 'order' => 4],
                    ['text' => 'الجنوبي', 'order' => 5],
                ],
            ],
            // Commented out: رتّب الكلمات لتكوين جملة صحيحة: من – الإنسان – ملعب – يتكون – كثيرة – جسم – عظام
        ];

        $connectQuestions = [
            [
                'title' => 'صِلْ بين الحيوان وبيئته المناسبة:',
                'type' => 'connect',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'language' => 'ar',
                'pairs' => [
                    ['left' => 'دب قطبي', 'right' => 'القطب الشمالي', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'جمل', 'right' => 'الصحراء', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'تمساح', 'right' => 'النهر', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
        ];

        // Take 3 true/false, 6 choice, 1 arrange, and 1 connect = 11 questions (within 10-12 range)
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 3),
            array_slice($choiceQuestions, 0, 6),
            array_slice($arrangeQuestions, 0, 1),
            $connectQuestions
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function createQuestions(int $trainingId, array $questions): void
    {
        foreach ($questions as $questionData) {
            $question = Question::create([
                'exam_training_id' => $trainingId,
                'title' => $questionData['title'],
                'type' => $questionData['type'],
                'language' => $questionData['language'],
                'xp' => $questionData['xp'],
                'coins' => $questionData['coins'],
                'marks' => $questionData['marks'],
            ]);

            if ($questionData['type'] === 'true_false') {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'text' => 'صح',
                    'is_correct' => $questionData['is_correct'],
                ]);
            } elseif ($questionData['type'] === 'choice') {
                foreach ($questionData['options'] as $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'text' => $option['text'],
                        'is_correct' => $option['is_correct'],
                    ]);
                }
            } elseif ($questionData['type'] === 'arrange') {
                foreach ($questionData['options'] as $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'text' => $option['text'],
                        'arrange_order' => $option['order'],
                    ]);
                }
            } elseif ($questionData['type'] === 'connect') {
                foreach ($questionData['pairs'] as $pair) {
                    $leftOption = QuestionOption::create([
                        'question_id' => $question->id,
                        'text' => $pair['left'],
                        'side' => 'left',
                    ]);

                    $rightOption = QuestionOption::create([
                        'question_id' => $question->id,
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
        }
    }

    private function addVideoRecycling(JourneyStage $stage): void
    {
        // Find video that is NOT linked to a lesson (has related_training_id or will be linked to training)
        // First, try to find a video that is already linked to a training (for journey)
        $video = Video::where('title_ar', 'إعادة التدوير')
            ->whereNotNull('related_training_id')
            ->first();

        // If not found, find a video that is not linked to any training yet
        // Take the second video (skip the first one which is for lesson)
        if (!$video) {
            $video = Video::where('title_ar', 'إعادة التدوير')
                ->whereNull('related_training_id')
                ->orderBy('id', 'asc')
                ->skip(1) // Skip the first video (for lesson)
                ->first();
        }

        if (!$video) {
            $this->command->warn('⚠️ Video "إعادة التدوير" not found for journey. Please run VideoSeeder first.');
            return;
        }

        // Create training and link video to it (if not already linked)
        $training = $this->createRecyclingTraining();
        if (!$video->related_training_id) {
            $video->update(['related_training_id' => $training->id]);
            $this->command->info("   🔗 Linked video to training for journey: {$training->title_ar} (Video ID: {$video->id})");
        }

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'video',
            'content_id' => $video->id,
        ]);
    }

    private function addThirdStageTraining1(JourneyStage $stage): void
    {
        $training = $this->createThirdStageTraining1();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function addThirdStageTraining2(JourneyStage $stage): void
    {
        $training = $this->createThirdStageTraining2();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function createRecyclingTraining(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Recycling Training',
            'title_ar' => 'إعادة التدوير',
            'description' => 'Training for recycling video',
            'description_ar' => 'تمرين فيديو إعادة التدوير',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createRecyclingQuestions($training->id);

        return $training;
    }

    private function createRecyclingQuestions(int $trainingId): void
    {
        $totalXp = 24;
        $totalCoins = 12;
        $totalMarks = 24;
        $questionsCount = 12;
        $xpPerQuestion = 2;
        $coinsPerQuestion = 1;
        $marksPerQuestion = 1;

        $trueFalseQuestions = [
            [
                'title' => 'عاد رامي من المدرسة دون رغبة في تطبيق الدرس.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'is_correct' => false,
            ],
            [
                'title' => 'استخدم رامي مقصاً وحبلاً وزجاجة بلاستيكية وغراء.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'is_correct' => true,
            ],
            [
                'title' => 'قام رامي بتثبيت الجزء العلوي من الزجاجة فوق الجزء السفلي باستخدام الغراء.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'is_correct' => true,
            ],
            // Commented out: لم يستعن رامي بأي شخص أثناء تنفيذ التجربة.
            // Commented out: لم يهتم رامي بمتابعة كمية الطعام داخل أداة الإطعام.
        ];

        $choiceQuestions = [
            [
                'title' => 'عاد رامي من المدرسة وهو يشعر بـ _________ لتطبيق درس إعادة التدوير.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'الحزن', 'is_correct' => false],
                    ['text' => 'الخوف', 'is_correct' => false],
                    ['text' => 'الحماس', 'is_correct' => true],
                    ['text' => 'الملل', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'استخدم رامي لصنع أداة إطعام الطيور زجاجة _________ فارغة.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'زجاجية', 'is_correct' => false],
                    ['text' => 'بلاستيكية', 'is_correct' => true],
                    ['text' => 'معدنية', 'is_correct' => false],
                    ['text' => 'خشبية', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ساعد والد رامي في عملية _________ الزجاجة البلاستيكية إلى قسمين.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'غسل', 'is_correct' => false],
                    ['text' => 'قص', 'is_correct' => true],
                    ['text' => 'طلاء', 'is_correct' => false],
                    ['text' => 'تلوين', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'فتح رامي ثقبين في أعلى الزجاجة لتثبيت _________.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'الزر', 'is_correct' => false],
                    ['text' => 'الغطاء', 'is_correct' => false],
                    ['text' => 'الحبل', 'is_correct' => true],
                    ['text' => 'الورق', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'من العوامل التي أخذها رامي في الاعتبار: أنواع الأشجار، المتابعة الدورية لكمية الطعام، وكمية الطعام _________.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'المهدرة', 'is_correct' => true],
                    ['text' => 'الملونة', 'is_correct' => false],
                    ['text' => 'الطازجة', 'is_correct' => false],
                    ['text' => 'المجمدة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ما الدرس الذي أراد رامي تطبيقه بعد عودته من المدرسة؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'درس الزراعة', 'is_correct' => false],
                    ['text' => 'درس إعادة التدوير', 'is_correct' => true],
                    ['text' => 'درس النظافة', 'is_correct' => false],
                    ['text' => 'درس الطيور', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ما المادة الأساسية التي استخدمها رامي لصنع أداة إطعام الطيور؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'علبة كرتونية', 'is_correct' => false],
                    ['text' => 'قنينة زجاجية', 'is_correct' => false],
                    ['text' => 'زجاجة بلاستيكية فارغة', 'is_correct' => true],
                    ['text' => 'علبة معدنية', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'من الذي ساعد رامي في قصّ الزجاجة البلاستيكية؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'أخوه', 'is_correct' => false],
                    ['text' => 'صديقه', 'is_correct' => false],
                    ['text' => 'معلمه', 'is_correct' => false],
                    ['text' => 'والده', 'is_correct' => true],
                ],
            ],
            [
                'title' => 'لماذا قام رامي بفتح ثقبين في أعلى الزجاجة؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => $xpPerQuestion,
                'coins' => $coinsPerQuestion,
                'marks' => $marksPerQuestion,
                'options' => [
                    ['text' => 'لتزيينها', 'is_correct' => false],
                    ['text' => 'لتثبيت الحبل وتعليق الأداة', 'is_correct' => true],
                    ['text' => 'لزيادة حجم الطعام', 'is_correct' => false],
                    ['text' => 'ليدخل الهواء إليها', 'is_correct' => false],
                ],
            ],
            // Commented out: ما أحد العوامل التي أخذها رامي في الاعتبار لضمان نجاح التجربة؟
        ];

        // Take 3 true/false and 9 choice = 12 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 3),
            array_slice($choiceQuestions, 0, 9)
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function createThirdStageTraining1(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training - Grammar',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Third stage first training - Grammar questions',
            'description_ar' => 'أسئلة نحوية',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createThirdStageTraining1Questions($training->id);

        return $training;
    }

    private function createThirdStageTraining1Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'الضمائر مثل: هو – هي – نحن تُعدّ أسماء.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            [
                'title' => 'الاسم هو ما دلّ على معنى في نفسه ويقترن بزمن.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => false,
            ],
            [
                'title' => 'كلمة (أكبر) هي اسم تفضيل.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            // Commented out: الصفة تأتي قبل الموصوف دائمًا.
            // Commented out: يا محمدُ: كلمة (محمد) منادى منصوب.
        ];

        $choiceQuestions = [
            [
                'title' => 'جمع كلمة (كتاب) هو ____________.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'كاتب', 'is_correct' => false],
                    ['text' => 'مكتبة', 'is_correct' => false],
                    ['text' => 'كتب', 'is_correct' => true],
                    ['text' => 'كتّاب', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ضد كلمة (قوي) هو ____________.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'كبير', 'is_correct' => false],
                    ['text' => 'سريع', 'is_correct' => false],
                    ['text' => 'ضعيف', 'is_correct' => true],
                    ['text' => 'قصير', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الاسم الذي لا يتغير آخره مع الإعراب يسمّى ____________.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'معربًا', 'is_correct' => false],
                    ['text' => 'مفردًا', 'is_correct' => false],
                    ['text' => 'جمعًا', 'is_correct' => false],
                    ['text' => 'مبنيًا', 'is_correct' => true],
                ],
            ],
            [
                'title' => 'من علامات الاسم دخول حرف ____________ عليه.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'النفي', 'is_correct' => false],
                    ['text' => 'الجر', 'is_correct' => true],
                    ['text' => 'النداء', 'is_correct' => false],
                    ['text' => 'الجزم', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'علامة جمع المؤنث السالم:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'ون', 'is_correct' => false],
                    ['text' => 'ان', 'is_correct' => false],
                    ['text' => 'ات', 'is_correct' => true],
                    ['text' => 'ين', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الفعل المضارع في الجملة: (الولد يقرأ القصة) هو:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'يقرأُ', 'is_correct' => true],
                    ['text' => 'الولد', 'is_correct' => false],
                    ['text' => 'القصة', 'is_correct' => false],
                    ['text' => 'الكتاب', 'is_correct' => false],
                ],
            ],
            // Commented out: معنى كلمة (استيقظ)
            // Commented out: الجملة التي تبدأ باسم تسمّى
        ];

        $connectQuestions = [
            [
                'title' => 'صِل الفعل بالفاعل المناسب:',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'كتبَ', 'right' => 'الطالبُ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'تأكلُ', 'right' => 'البنتُ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'يركضُ', 'right' => 'الولدُ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'تطيرُ', 'right' => 'العصفورةُ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'يسبحُ', 'right' => 'الطفلُ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
            [
                'title' => 'صِل الكلمة بمفردها الصحيح:',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'رجال', 'right' => 'رجل', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'كتب', 'right' => 'كتاب', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'أطفال', 'right' => 'طفل', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'بيوت', 'right' => 'بيت', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'أسواق', 'right' => 'سوق', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
        ];

        // Take 3 true/false, 6 choice, and 2 connect = 11 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 3),
            array_slice($choiceQuestions, 0, 6),
            $connectQuestions
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function createThirdStageTraining2(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training - Science',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Third stage second training - Science questions',
            'description_ar' => 'أسئلة علمية',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createThirdStageTraining2Questions($training->id);

        return $training;
    }

    private function createThirdStageTraining2Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'يحتاج النبات إلى ضوء الشمس لصنع غذائه.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            [
                'title' => 'القلب جزء من الجهاز الهضمي.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => false,
            ],
            [
                'title' => 'تدور الأرض حول الشمس.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            // Commented out: يتجمّد الماء عند درجة 0 م°.
            // Commented out: ينتقل الصوت أسرع من الضوء.
        ];

        $choiceQuestions = [
            [
                'title' => 'ما الذي تحتاجه الحيوانات للبقاء على قيد الحياة؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الذهب', 'is_correct' => false],
                    ['text' => 'الهواء', 'is_correct' => true],
                    ['text' => 'البلاستيك', 'is_correct' => false],
                    ['text' => 'الرخام', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'أكبر كوكب في المجموعة الشمسية هو:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'عطارد', 'is_correct' => false],
                    ['text' => 'المريخ', 'is_correct' => false],
                    ['text' => 'المشتري', 'is_correct' => true],
                    ['text' => 'الأرض', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'أي عضو يضخ الدم داخل الجسم؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الرئة', 'is_correct' => false],
                    ['text' => 'الكبد', 'is_correct' => false],
                    ['text' => 'القلب', 'is_correct' => true],
                    ['text' => 'المعدة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'القوة التي تسحب الأجسام نحو الأرض هي:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الاحتكاك', 'is_correct' => false],
                    ['text' => 'الجاذبية', 'is_correct' => true],
                    ['text' => 'المغناطيسية', 'is_correct' => false],
                    ['text' => 'الدفع', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'أي جزء من النبات يمتص الماء؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الساق', 'is_correct' => false],
                    ['text' => 'الجذر', 'is_correct' => true],
                    ['text' => 'الورقة', 'is_correct' => false],
                    ['text' => 'البذرة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'تتحول المادة من صلب إلى سائل عندما يتم:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'تبريدها', 'is_correct' => false],
                    ['text' => 'تجميدها', 'is_correct' => false],
                    ['text' => 'إذابتها', 'is_correct' => true],
                    ['text' => 'تكثيفها', 'is_correct' => false],
                ],
            ],
            // Commented out: عملية تحول الماء إلى بخار تسمى
            // Commented out: الطاقة القادمة من الشمس تسمى طاقة
            // Commented out: يتنفس الإنسان ___ ويطلق ثاني أكسيد الكربون
            // Commented out: ___ مركز المجموعة الشمسية
            // Commented out: حالات المادة ثلاث: صلب وسائل و___
            // Commented out: المطر والثلج والبرد هي أشكال من ___
        ];

        $connectQuestions = [
            [
                'title' => 'صِل الكلمة بما يناسبها',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'بطارية', 'right' => 'طاقة', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'سلك', 'right' => 'توصيل', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'مصباح', 'right' => 'ضوء', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'دائرة', 'right' => 'كهرباء', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'زر', 'right' => 'تشغيل', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
            [
                'title' => 'صِل الكلمة بما يناسبها',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'شمس', 'right' => 'ضوء', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'نار', 'right' => 'حرارة', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'رياح', 'right' => 'حركة', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'طعام', 'right' => 'جسم', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'ماء', 'right' => 'شلال', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
        ];

        // Take 3 true/false, 6 choice, and 2 connect = 11 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 3),
            array_slice($choiceQuestions, 0, 6),
            $connectQuestions
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function addFourthStageTraining1(JourneyStage $stage): void
    {
        $training = $this->createFourthStageTraining1();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function addFourthStageTraining2(JourneyStage $stage): void
    {
        $training = $this->createFourthStageTraining2();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function createFourthStageTraining1(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training - Religious',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Fourth stage first training - Religious questions',
            'description_ar' => 'أسئلة دينية',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createFourthStageTraining1Questions($training->id);

        return $training;
    }

    private function createFourthStageTraining1Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'قال تعالى: ﴿… لَهُ مَا فِي السَّمَاوَاتِ وَمَا فِي الْأَرْضِ …﴾ هذه آية من آية الكرسي.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            [
                'title' => 'صيام رمضان واجب على كل مسلم بالغ عاقل.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            [
                'title' => 'الزكاة هي الركن الخامس من أركان الإسلام.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => false,
            ],
            [
                'title' => 'قال تعالى عن نوح عليه السلام: ﴿… فَأَنْجَيْنَاهُ وَأَصْحَابَ السَّفِينَةِ …﴾ تتحدث عن نجاته وقومه.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            // Commented out: أبو بكر الصديق رضي الله عنه كان يختم القرآن في: الصلاة وقيام الليل.
            // Commented out: من فضل تعلم القرآن الكريم أنه يرفع الدرجات في الدنيا والآخرة.
            // Commented out: كان النمرود ملكًا ظالمًا، وكان سيدنا إبراهيم عليه السلام يناقشه بالحجة.
        ];

        $choiceQuestions = [
            [
                'title' => 'عدد آيات سورة العلق:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => '90', 'is_correct' => false],
                    ['text' => '95', 'is_correct' => false],
                    ['text' => '19', 'is_correct' => true],
                    ['text' => '98', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'من هو خليل الله؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'موسى عليه السلام', 'is_correct' => false],
                    ['text' => 'عيسى عليه السلام', 'is_correct' => false],
                    ['text' => 'محمد ﷺ', 'is_correct' => false],
                    ['text' => 'إبراهيم عليه السلام', 'is_correct' => true],
                ],
            ],
            [
                'title' => 'ما هو أول ركن من أركان الإسلام؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الصلاة', 'is_correct' => false],
                    ['text' => 'الصيام', 'is_correct' => false],
                    ['text' => 'الشهادتان', 'is_correct' => true],
                    ['text' => 'الزكاة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'خروج الإنسان من بلده إلى بلد آخر للإقامة فيه يسمى:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الجهاد', 'is_correct' => false],
                    ['text' => 'السفر', 'is_correct' => false],
                    ['text' => 'الهجرة', 'is_correct' => true],
                    ['text' => 'الفتح', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ليلة مباركة من ليالي العشر الأواخر من رمضان هي:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'نصف شعبان', 'is_correct' => false],
                    ['text' => 'ليلة القدر', 'is_correct' => true],
                    ['text' => 'ليلة عرفة', 'is_correct' => false],
                    ['text' => 'ليلة الهجرة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'كم عدد أجزاء القرآن الكريم؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => '20', 'is_correct' => false],
                    ['text' => '30', 'is_correct' => true],
                    ['text' => '40', 'is_correct' => false],
                    ['text' => '10', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'اعتاد الرسول ﷺ أن يُلقَّب قبل الإسلام بالصادق _____.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الأمين', 'is_correct' => true],
                    ['text' => 'الرحيم', 'is_correct' => false],
                    ['text' => 'الحكيم', 'is_correct' => false],
                    ['text' => 'الشجاع', 'is_correct' => false],
                ],
            ],
            // Commented out: الهجرة النبوية كانت من مكة إلى _____.
        ];

        // Take 4 true/false and 7 choice = 11 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 4),
            array_slice($choiceQuestions, 0, 7)
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function createFourthStageTraining2(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training - Science 2',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Fourth stage second training - Science questions',
            'description_ar' => 'التمرين الثاني',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createFourthStageTraining2Questions($training->id);

        return $training;
    }

    private function createFourthStageTraining2Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'القلب يضخ الدم داخل الجسم.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            [
                'title' => 'النباتات تطلق غاز ثاني أكسيد الكربون أثناء البناء الضوئي.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => false,
            ],
            [
                'title' => 'يتجمّد الماء عند 0 م°.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            // Commented out: تحتاج الحيوانات إلى الهواء للبقاء.
            // Commented out: الضوء ينتقل أسرع من الصوت.
        ];

        $choiceQuestions = [
            [
                'title' => 'أي غاز تُطلقه النباتات خلال عملية البناء الضوئي؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'النيتروجين', 'is_correct' => false],
                    ['text' => 'ثاني أكسيد الكربون', 'is_correct' => false],
                    ['text' => 'غير معروف', 'is_correct' => false],
                    ['text' => 'الأكسجين', 'is_correct' => true],
                ],
            ],
            [
                'title' => 'ما الذي يمتص الماء في النبات؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الساق', 'is_correct' => false],
                    ['text' => 'الجذر', 'is_correct' => true],
                    ['text' => 'الورقة', 'is_correct' => false],
                    ['text' => 'البذرة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ما هو مقياس الحرارة؟',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الطول', 'is_correct' => false],
                    ['text' => 'الوزن', 'is_correct' => false],
                    ['text' => 'الحرارة', 'is_correct' => true],
                    ['text' => 'الصوت', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'دوران الأرض حول نفسها يسمى:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'ثوران', 'is_correct' => false],
                    ['text' => 'دوران', 'is_correct' => true],
                    ['text' => 'انعكاس', 'is_correct' => false],
                    ['text' => 'ميل', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'نرى الأشياء لأن الضوء ___ عنها.',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'يختفي', 'is_correct' => false],
                    ['text' => 'يمتص', 'is_correct' => false],
                    ['text' => 'ينعكس', 'is_correct' => true],
                    ['text' => 'يتجمد', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الطاقة الشمسية تعتبر من مصادر الطاقة:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الفحم', 'is_correct' => false],
                    ['text' => 'النفط', 'is_correct' => false],
                    ['text' => 'المتجددة', 'is_correct' => true],
                    ['text' => 'الغاز الطبيعي', 'is_correct' => false],
                ],
            ],
            // Commented out: أي مما يلي يعد شكلاً من أشكال الهطول؟
            // Commented out: الجزء من النبات الذي يقوم بصنع الغذاء
            // Commented out: أي جسم في الفضاء يدور حول الشمس؟
            // Commented out: الهيكل العظمي يحمي الجسم ويعطيه ___
            // Commented out: تتكون الكائنات الحية من وحدات صغيرة تسمى ___
            // Commented out: عملية تحول الغاز إلى سائل تسمى ___
        ];

        $connectQuestions = [
            [
                'title' => 'صِل الكلمة بما يناسبها',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'كوكب', 'right' => 'أرض', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'مدار', 'right' => 'قمر', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'نجم', 'right' => 'شمس', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'صخرة', 'right' => 'نيزك', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'نجوم', 'right' => 'مجرة', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
            [
                'title' => 'صِل الكلمة بما يناسبها',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'جذور', 'right' => 'نبات', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'حركة', 'right' => 'حيوان', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'دماغ', 'right' => 'إنسان', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'دقيقة', 'right' => 'بكتيريا', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'زعانف', 'right' => 'سمكة', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
            // Commented out: صِل الكلمة بما يناسبها (third connect question)
        ];

        // Take 3 true/false, 6 choice, and 2 connect = 11 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 3),
            array_slice($choiceQuestions, 0, 6),
            $connectQuestions
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

    private function addFifthStageTraining1(JourneyStage $stage): void
    {
        $training = $this->createFifthStageTraining1();

        StageContent::create([
            'stage_id' => $stage->id,
            'content_type' => 'examTraining',
            'content_id' => $training->id,
        ]);
    }

    private function createFifthStageTraining1(): ExamTraining
    {
        $training = ExamTraining::create([
            'title' => 'Journey Training - Mixed',
            'title_ar' => 'أسئلة رحلتي',
            'description' => 'Fifth stage training - Mixed questions',
            'description_ar' => 'أسئلة متنوعة',
            'type' => 'training',
            'duration' => null,
            'created_by' => 1,
            'subject_id' => null,
            'group_id' => null,
            'start_date' => Carbon::now(),
            'end_date' => null,
        ]);

        $this->createFifthStageTraining1Questions($training->id);

        return $training;
    }

    private function createFifthStageTraining1Questions(int $trainingId): void
    {
        $trueFalseQuestions = [
            [
                'title' => 'القمر جسم يعكس ضوء الشمس ولا يضيء من نفسه.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            [
                'title' => 'العدد 0 ليس عدداً زوجياً.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => false,
            ],
            [
                'title' => 'الماء يغلي عند درجة حرارة 100 مئوية.',
                'type' => 'true_false',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'is_correct' => true,
            ],
            // Commented out: الفعل المضارع يدل على حدث وقع في الماضي.
            // Commented out: اليابان تقع في قارة آسيا.
            // Commented out: العضلات تساعد الجسم على الحركة.
            // Commented out: الهواء لا يحتوي على أي غازات.
        ];

        $choiceQuestions = [
            [
                'title' => 'أكبر كوكب في المجموعة الشمسية هو:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الأرض', 'is_correct' => false],
                    ['text' => 'المشتري', 'is_correct' => true],
                    ['text' => 'الزهرة', 'is_correct' => false],
                    ['text' => 'المريخ', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'ناتج العملية 8 × 7 يساوي:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => '48', 'is_correct' => false],
                    ['text' => '56', 'is_correct' => true],
                    ['text' => '64', 'is_correct' => false],
                    ['text' => '72', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الفعل المضارع من كلمة (كتب) هو:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'يكتب', 'is_correct' => true],
                    ['text' => 'كاتب', 'is_correct' => false],
                    ['text' => 'مكتوب', 'is_correct' => false],
                    ['text' => 'كتابة', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الدولة التي عاصمتها الرياض هي:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الكويت', 'is_correct' => false],
                    ['text' => 'البحرين', 'is_correct' => false],
                    ['text' => 'السعودية', 'is_correct' => true],
                    ['text' => 'الأردن', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الحيوان الذي يتغذّى على النباتات فقط:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'النمر', 'is_correct' => false],
                    ['text' => 'الذئب', 'is_correct' => false],
                    ['text' => 'الأرنب', 'is_correct' => true],
                    ['text' => 'النسر', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'الشكل الذي له أربع أضلاع متساوية وزوايا قائمة:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'مستطيل', 'is_correct' => false],
                    ['text' => 'مربع', 'is_correct' => true],
                    ['text' => 'مثلث', 'is_correct' => false],
                    ['text' => 'معين', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'المعدن الذي يُستخدم في صناعة الأسلاك الكهربائية:',
                'type' => 'choice',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'options' => [
                    ['text' => 'الحديد', 'is_correct' => false],
                    ['text' => 'الفضة', 'is_correct' => false],
                    ['text' => 'النحاس', 'is_correct' => true],
                    ['text' => 'الذهب', 'is_correct' => false],
                ],
            ],
            // Commented out: العملية التي يتحول فيها الجليد إلى ماء تسمّى
            // Commented out: القارة التي تُعرف بالقارة السمراء
            // Commented out: الجهاز المسؤول عن ضخ الدم في جسم الإنسان
            // Commented out: أكبر محيط في العالم هو ______
            // Commented out: عدد أيام الأسبوع هو ______
            // Commented out: الحيوان الملقّب بملك الغابة هو ______
            // Commented out: العنصر الذي نتنفسه للبقاء هو ______
            // Commented out: العملية التي يصنع فيها النبات غذاءه تسمّى ______
            // Commented out: أسرع وسيلة نقل مما يلي هي ______
            // Commented out: شكل الأرض هو ______
            // Commented out: عدد قارات العالم هو ______
        ];

        $connectQuestions = [
            [
                'title' => 'صِل الفعل بالفاعل المناسب:',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'الشمس', 'right' => 'الضوء', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'القمر', 'right' => 'الليل', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'النبات', 'right' => 'النمو', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الماء', 'right' => 'الشرب', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الهواء', 'right' => 'التنفس', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
            [
                'title' => 'صِل الكلمة بمفردها الصحيح:',
                'type' => 'connect',
                'language' => 'ar',
                'xp' => 2,
                'coins' => 1,
                'marks' => 1,
                'pairs' => [
                    ['left' => 'القلب', 'right' => 'يضخ الدم', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الجمل', 'right' => 'سفينة الصحراء', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الكتاب', 'right' => 'يُقرأ', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الفهد', 'right' => 'أسرع', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                    ['left' => 'الطبيب', 'right' => 'يعالج', 'xp' => 2, 'coins' => 1, 'marks' => 1],
                ],
            ],
        ];

        // Take 3 true/false, 7 choice, and 2 connect = 12 questions
        $selectedQuestions = array_merge(
            array_slice($trueFalseQuestions, 0, 3),
            array_slice($choiceQuestions, 0, 7),
            $connectQuestions
        );

        shuffle($selectedQuestions); // Shuffle to mix types

        $this->createQuestions($trainingId, $selectedQuestions);
    }

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

    private function createBookFolders(string $folderName, int $numberOfPages): void
    {
        $basePath = storage_path("app/public/books/{$folderName}");

        File::makeDirectory($basePath, 0755, true, true);

        for ($i = 1; $i <= $numberOfPages; $i++) {
            File::makeDirectory("{$basePath}/pages/page_{$i}", 0755, true, true);
        }
    }
}