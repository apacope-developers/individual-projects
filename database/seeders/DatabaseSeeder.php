<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SchoolClass;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------
        // STEP 1: Create Admin
        // -----------------------------------------------
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'classroom'=> null,
            ]
        );

        // -----------------------------------------------
        // STEP 2: Define Teachers
        // -----------------------------------------------
        $teachers = [
            ['name' => 'Demo Teacher',   'email' => 'teacher@example.com',    'classroom' => 'L3SWD'],
            ['name' => 'Mugisha Arsene', 'email' => 'arsenem93@gmail.com',    'classroom' => 'L4SWD'],
            ['name' => 'Mugisha Arsene', 'email' => 'arsenem90@gmail.com',    'classroom' => 'L5SWD'],
            ['name' => 'Mutoni',         'email' => 'mutoni@gmail.com',       'classroom' => 'S6'],
            ['name' => 'John Teacher',   'email' => 'teacher@attendance.com', 'classroom' => 'S5'],
        ];

        // -----------------------------------------------
        // STEP 3: Define Students per Classroom
        // -----------------------------------------------
        $students = [
            'L3SWD' => [
                'Alice Uwimana', 'Bob Nkurunziza', 'Claire Mukamana', 'David Habimana',
                'Eve Nyirahabimana', 'Frank Bizimana', 'Grace Uwera', 'Henry Ndayishimiye',
                'Irene Mukandori', 'James Nsabimana',
            ],
            'L4SWD' => [
                'Kevin Habimana', 'Laura Mukamana', 'Mike Niyonzima', 'Nancy Uwimana',
                'Oscar Bizimungu', 'Patricia Nyiramana', 'Quinn Ndayisenga', 'Rachel Uwera',
                'Samuel Nkurunziza', 'Tina Mukandori',
            ],
            'L5SWD' => [
                'Ursula Habimana', 'Victor Nsabimana', 'Wendy Mukamana', 'Xavier Niyonzima',
                'Yvonne Uwimana', 'Zachary Bizimana', 'Agnes Nyiramana', 'Bruno Ndayishimiye',
                'Celine Uwera', 'Denis Nkurunziza',
            ],
            'S6' => [
                'Emma Mukandori', 'Felix Habimana', 'Gloria Nsabimana', 'Hector Niyonzima',
                'Ingrid Uwimana', 'Joel Bizimana', 'Karen Nyiramana', 'Leo Ndayishimiye',
                'Maria Uwera', 'Nathan Nkurunziza',
            ],
            'S5' => [
                'Olivia Mukamana', 'Paul Habimana', 'Queen Nsabimana', 'Robert Niyonzima',
                'Sandra Uwimana', 'Thomas Bizimana', 'Uma Nyiramana', 'Vincent Ndayishimiye',
                'Wanda Uwera', 'Xander Nkurunziza',
            ],
        ];

        // -----------------------------------------------
        // STEP 4: Create Teachers + Classrooms + Students
        // -----------------------------------------------
        foreach ($teachers as $teacherData) {
            // Create teacher
            $teacher = User::firstOrCreate(
                ['email' => $teacherData['email']],
                [
                    'name'      => $teacherData['name'],
                    'password'  => Hash::make('teacher123'),
                    'role'      => 'teacher',
                    'classroom' => $teacherData['classroom'],
                ]
            );

            // Create classroom and assign teacher
            $class = SchoolClass::firstOrCreate(
                ['name' => $teacherData['classroom']],
                [
                    'description'   => $teacherData['classroom'] . ' Class',
                    'teacher_id'    => $teacher->id,
                    'capacity'      => 30,
                    'grade_level'   => $teacherData['classroom'],
                    'academic_year' => '2025-2026',
                    'is_active'     => true,
                ]
            );

            // Create students for this classroom
            foreach ($students[$teacherData['classroom']] as $studentName) {
                $emailSlug = strtolower(str_replace(' ', '.', $studentName));
                User::firstOrCreate(
                    ['email' => $emailSlug . '@student.com'],
                    [
                        'name'      => $studentName,
                        'password'  => Hash::make('student123'),
                        'role'      => 'student',
                        'classroom' => $teacherData['classroom'],
                    ]
                );
            }
        }

        echo "\n✅ Done! Created:\n";
        echo "   - 1 Admin (admin@gmail.com / admin123)\n";
        echo "   - 5 Teachers (password: teacher123)\n";
        echo "   - 5 Classrooms (L3SWD, L4SWD, L5SWD, S6, S5)\n";
        echo "   - 50 Students (password: student123)\n\n";
    }
}