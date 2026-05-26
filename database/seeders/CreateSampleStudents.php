<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateSampleStudents extends Seeder
{
    public function run(): void
    {
        // Define classrooms and sample students
        $classroomStudents = [
            'L3SWD' => [
                ['Alice Johnson', 'alice.l3@school.com'],
                ['Bob Smith', 'bob.l3@school.com'],
                ['Carol Williams', 'carol.l3@school.com'],
                ['David Brown', 'david.l3@school.com'],
                ['Emma Davis', 'emma.l3@school.com'],
            ],
            'L4SWD' => [
                ['Frank Miller', 'frank.l4@school.com'],
                ['Grace Wilson', 'grace.l4@school.com'],
                ['Henry Moore', 'henry.l4@school.com'],
                ['Iris Taylor', 'iris.l4@school.com'],
                ['Jack Anderson', 'jack.l4@school.com'],
            ],
            'L5SWD' => [
                ['Kate Thomas', 'kate.l5@school.com'],
                ['Liam Jackson', 'liam.l5@school.com'],
                ['Mia White', 'mia.l5@school.com'],
                ['Noah Harris', 'noah.l5@school.com'],
                ['Olivia Martin', 'olivia.l5@school.com'],
            ],
            'S6' => [
                ['Paul Clark', 'paul.s6@school.com'],
                ['Quinn Lewis', 'quinn.s6@school.com'],
                ['Rachel Walker', 'rachel.s6@school.com'],
                ['Sam Hall', 'sam.s6@school.com'],
                ['Tina Allen', 'tina.s6@school.com'],
            ],
            'S5' => [
                ['Uma Young', 'uma.s5@school.com'],
                ['Victor King', 'victor.s5@school.com'],
                ['Wendy Scott', 'wendy.s5@school.com'],
                ['Xavier Green', 'xavier.s5@school.com'],
                ['Yara Adams', 'yara.s5@school.com'],
            ],
        ];

        // Create students for each classroom
        foreach ($classroomStudents as $classroom => $students) {
            foreach ($students as [$name, $email]) {
                // Check if student already exists
                $existingStudent = User::where('email', $email)->first();
                
                if (!$existingStudent) {
                    User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => \Illuminate\Support\Facades\Hash::make('student123'),
                        'role' => 'student',
                        'classroom' => $classroom,
                    ]);
                    echo "Created student: {$name} for classroom {$classroom}\n";
                } else {
                    // Update existing student's classroom if needed
                    $existingStudent->classroom = $classroom;
                    $existingStudent->save();
                    echo "Updated student: {$name} to classroom {$classroom}\n";
                }
            }
        }

        echo "\nSample students created for all classrooms!\n";
        echo "Each classroom now has 5 students.\n";
        echo "Student login password: student123\n";
    }
}
