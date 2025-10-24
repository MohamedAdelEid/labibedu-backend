<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        // Primary levels (Kindergarten)
        $primaryGrades = [
            'KG One',
            'KG Two',
        ];
        foreach ($primaryGrades as $grade) {
            Grade::create(['name' => $grade, 'level' => 'kindergarten']);
        }

        // Preparatory levels (Primary)
        $preparatoryGrades = [
            'Grade One',
            'Grade Two',
            'Grade Three',
            'Grade Four',
            'Grade Five',
            'Grade Six',
        ];
        foreach ($preparatoryGrades as $grade) {
            Grade::create(['name' => $grade, 'level' => 'primary']);
        }   

        // Secondary levels (Preparatory)
        $secondaryGrades = [
            'Grade One',
            'Grade Two',
            'Grade Three',
        ];
        foreach ($secondaryGrades as $grade) {
            Grade::create(['name' => $grade, 'level' => 'preparatory']);
        }

        // Secondary levels (Secondary)
        $secondaryGrades = [
            'Grade One',
            'Grade Two',
            'Grade Three',
        ];
        foreach ($secondaryGrades as $grade) {
            Grade::create(['name' => $grade, 'level' => 'secondary']);
        }
    }
}