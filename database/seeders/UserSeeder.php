<?php

namespace Database\Seeders;

use App\Domain\Enums\UserRole;
use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'user_name' => 'admin',
            'email' => 'admin@labibedu.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::ADMIN,
            'name' => 'Admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'user_name' => 'teacher1',
            'email' => 'teacher@labibedu.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::TEACHER,
            'name' => 'Teacher',
            'email_verified_at' => now(),
        ]);

        // Create 4 students - all in Grade 5 (classroom_id = 1)
        $students = [
            [
                'name' => 'User 1',
                'user_name' => 'user1',
                'email' => 'user1@student.com',
                'school_id' => 1,
                'classroom_id' => 1, // Grade 5
                'group_id' => 1,
                'date_of_birth' => '2010-01-01',
            ],
            [
                'name' => 'User 2',
                'user_name' => 'user2',
                'email' => 'user2@student.com',
                'school_id' => 1,
                'classroom_id' => 1, // Grade 5
                'group_id' => 1,
                'date_of_birth' => '2010-02-02',
            ],
            [
                'name' => 'User 3',
                'user_name' => 'user3',
                'email' => 'user3@student.com',
                'school_id' => 1,
                'classroom_id' => 1, // Grade 5
                'group_id' => 1,
                'date_of_birth' => '2010-03-03',
            ],
            [
                'name' => 'User 4',
                'user_name' => 'user4',
                'email' => 'user4@student.com',
                'school_id' => 1,
                'classroom_id' => 1, // Grade 5
                'group_id' => 1,
                'date_of_birth' => '2010-04-04',
            ],
        ];

        foreach ($students as $studentData) {
            // Create the user
            $user = User::create([
                'user_name' => $studentData['user_name'],
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password123'),
                'role' => UserRole::STUDENT,
                'email_verified_at' => now(),
            ]);

            // Create the student profile - all in Grade 5
            Student::create([
                'user_id' => $user->id,
                'school_id' => $studentData['school_id'],
                'classroom_id' => $studentData['classroom_id'],
                'group_id' => $studentData['group_id'],
                'date_of_birth' => $studentData['date_of_birth'],
                'xp' => 0,
                'coins' => 0,
            ]);
        }
    }
}