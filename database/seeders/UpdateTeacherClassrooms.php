<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateTeacherClassrooms extends Seeder
{
    public function run(): void
    {
        // Get all teachers and assign unique classrooms
        $teachers = User::where('role', 'teacher')->get();
        
        $classrooms = [
            'L3SWD',
            'L4SWD', 
            'L5SWD',
            'S6',
            'S5',
            'Class A',
            'Class B',
            'Class C',
        ];

        $teacherClassroomAssignments = [
            'teacher@example.com' => 'L3SWD',
            'arsenem93@gmail.com' => 'L4SWD',
            'arsenem90@gmail.com' => 'L5SWD',
            'mutoni@gmail.com' => 'S6',
            'teacher@attendance.com' => 'S5',
        ];

        foreach ($teachers as $teacher) {
            if (isset($teacherClassroomAssignments[$teacher->email])) {
                $teacher->classroom = $teacherClassroomAssignments[$teacher->email];
                $teacher->save();
                echo "Updated teacher {$teacher->name} ({$teacher->email}) -> Classroom: {$teacher->classroom}\n";
            } else {
                // Assign remaining classrooms to any other teachers
                $remainingClassrooms = array_diff($classrooms, array_values($teacherClassroomAssignments));
                if (!empty($remainingClassrooms)) {
                    $randomClassroom = array_shift($remainingClassrooms);
                    $teacher->classroom = $randomClassroom;
                    $teacher->save();
                    echo "Assigned teacher {$teacher->name} ({$teacher->email}) -> Classroom: {$teacher->classroom}\n";
                }
            }
        }

        echo "\nTeacher classroom assignments completed!\n";
    }
}
