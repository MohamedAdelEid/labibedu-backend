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

        $student1User = User::create(attributes: [
            'user_name' => 'student1',
            'email' => 'student@labibedu.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::STUDENT,
            'name' => 'Student',
            'email_verified_at' => now(),
        ]);

        // Create student profile for student1
        Student::create([
            'user_id' => $student1User->id,
            'school_id' => 1,
            'classroom_id' => 1,
            'group_id' => 1,
            'date_of_birth' => '2010-01-01',
            'xp' => 50,
            'coins' => 25,
        ]);

        // Teachers
        $teachers = [
            ['name' => 'Ahmed Hassan', 'user_name' => 'ahmed.hassan', 'email' => 'ahmed.hassan@labibedu.com'],
            ['name' => 'Fatima Ali', 'user_name' => 'fatima.ali', 'email' => 'fatima.ali@labibedu.com'],
            ['name' => 'Mohamed Ibrahim', 'user_name' => 'mohamed.ibrahim', 'email' => 'mohamed.ibrahim@labibedu.com'],
            ['name' => 'Sara Mahmoud', 'user_name' => 'sara.mahmoud', 'email' => 'sara.mahmoud@labibedu.com'],
            ['name' => 'Omar Khaled', 'user_name' => 'omar.khaled', 'email' => 'omar.khaled@labibedu.com'],
        ];

        foreach ($teachers as $teacher) {
            User::create([
                'user_name' => $teacher['user_name'],
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('password123'),
                'role' => UserRole::TEACHER,
                'email_verified_at' => now(),
            ]);
        }

        // Students with classroom, group, and school assignments
        $students = [
            [
                'name' => 'Youssef Ahmed',
                'user_name' => 'youssef.ahmed',
                'email' => 'youssef.ahmed@student.com',
                'school_id' => 1,
                'classroom_id' => 1,
                'group_id' => 1,
                'date_of_birth' => '2010-05-15',
            ],
            [
                'name' => 'Nour Mohamed',
                'user_name' => 'nour.mohamed',
                'email' => 'nour.mohamed@student.com',
                'school_id' => 1,
                'classroom_id' => 1,
                'group_id' => 2,
                'date_of_birth' => '2010-08-22',
            ],
            [
                'name' => 'Layla Hassan',
                'user_name' => 'layla.hassan',
                'email' => 'layla.hassan@student.com',
                'school_id' => 1,
                'classroom_id' => 2,
                'group_id' => 4,
                'date_of_birth' => '2010-03-10',
            ],
            [
                'name' => 'Karim Ali',
                'user_name' => 'karim.ali',
                'email' => 'karim.ali@student.com',
                'school_id' => 1,
                'classroom_id' => 2,
                'group_id' => 5,
                'date_of_birth' => '2010-12-05',
            ],
            [
                'name' => 'Maryam Ibrahim',
                'user_name' => 'maryam.ibrahim',
                'email' => 'maryam.ibrahim@student.com',
                'school_id' => 2,
                'classroom_id' => 3,
                'group_id' => 7,
                'date_of_birth' => '2010-07-18',
            ],
            [
                'name' => 'Adam Khaled',
                'user_name' => 'adam.khaled',
                'email' => 'adam.khaled@student.com',
                'school_id' => 2,
                'classroom_id' => 3,
                'group_id' => 8,
                'date_of_birth' => '2010-11-30',
            ],
            [
                'name' => 'Hana Mahmoud',
                'user_name' => 'hana.mahmoud',
                'email' => 'hana.mahmoud@student.com',
                'school_id' => 2,
                'classroom_id' => 4,
                'group_id' => 10,
                'date_of_birth' => '2010-04-12',
            ],
            [
                'name' => 'Zain Omar',
                'user_name' => 'zain.omar',
                'email' => 'zain.omar@student.com',
                'school_id' => 3,
                'classroom_id' => 5,
                'group_id' => 13,
                'date_of_birth' => '2010-09-25',
            ],
            [
                'name' => 'Salma Youssef',
                'user_name' => 'salma.youssef',
                'email' => 'salma.youssef@student.com',
                'school_id' => 3,
                'classroom_id' => 5,
                'group_id' => 14,
                'date_of_birth' => '2010-01-08',
            ],
            [
                'name' => 'Rami Nour',
                'user_name' => 'rami.nour',
                'email' => 'rami.nour@student.com',
                'school_id' => 3,
                'classroom_id' => 6,
                'group_id' => 16,
                'date_of_birth' => '2010-06-14',
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

            // Create the student profile with classroom, group, and school assignments
            Student::create([
                'user_id' => $user->id,
                'school_id' => $studentData['school_id'],
                'classroom_id' => $studentData['classroom_id'],
                'group_id' => $studentData['group_id'],
                'date_of_birth' => $studentData['date_of_birth'],
                'xp' => rand(0, 100),
                'coins' => rand(0, 50),
            ]);
        }
    }
}