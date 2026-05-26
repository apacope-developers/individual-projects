<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@attendance.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'classroom' => null,
            ]
        );

        // Create sample teacher
        User::updateOrCreate(
            ['email' => 'teacher@attendance.com'],
            [
                'name' => 'John Teacher',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'classroom' => 'Class A',
            ]
        );

        // Create sample students
        $students = [
            ['name' => 'Alice Student', 'email' => 'alice@attendance.com', 'classroom' => 'Class A'],
            ['name' => 'Bob Student', 'email' => 'bob@attendance.com', 'classroom' => 'Class A'],
            ['name' => 'Charlie Student', 'email' => 'charlie@attendance.com', 'classroom' => 'Class B'],
        ];

        foreach ($students as $studentData) {
            User::updateOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'classroom' => $studentData['classroom'],
                ]
            );
        }
    }
}
