<?php

namespace App\Domain\Interfaces\Services;

use App\Infrastructure\Models\Student;

interface StudentServiceInterface
{
    /**
     * Update student's XP and coins
     *
     * @param int $studentId
     * @param int $xpGained
     * @param int $coinsGained
     * @param string $reason
     * @return array
     */
    public function updateStudentScoring(int $studentId, int $xpGained, int $coinsGained, string $reason = ''): array;

    /**
     * Get student's complete scoring summary
     *
     * @param Student $student
     * @return array
     */
    public function getStudentScoringSummary(Student $student): array;

    /**
     * Recalculate student's total XP and coins from all activities
     *
     * @param Student $student
     * @return array
     */
    public function recalculateStudentTotals(Student $student): array;

    /**
     * Award bonus points for achievements
     *
     * @param Student $student
     * @param int $xpBonus
     * @param int $coinsBonus
     * @param string $reason
     * @return array
     */
    public function awardBonus(Student $student, int $xpBonus, int $coinsBonus, string $reason): array;

    /**
     * Update student profile information
     *
     * @param Student $student
     * @param array $data
     * @return Student
     */
    public function updateStudentProfile(Student $student, array $data): Student;

    /**
     * Get complete student profile data
     *
     * @param int $studentId
     * @return array
     */
    public function getStudentProfile(int $studentId): array;

    /**
     * Complete first-time student setup
     *
     * @param int $studentId
     * @param array $data
     * @return array
     */
    public function completeFirstSetup(int $studentId, array $data): array;

    /**
     * Get student settings
     *
     * @param int $studentId
     * @return array
     */
    public function getSettings(int $studentId): array;

    /**
     * Update student settings
     *
     * @param int $studentId
     * @param array $data
     * @return array
     */
    public function updateSettings(int $studentId, array $data): array;

    /**
     * Complete first-time setup (mark is_first_time as false)
     *
     * @param int $studentId
     * @return array
     */
    public function completeFirstTime(int $studentId): array;
}