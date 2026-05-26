<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class FixTeacherPasswords extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        
        foreach ($teachers as $teacher) {
            $teacher->password = \Illuminate\Support\Facades\Hash::make('teacher123');
            $teacher->save();
            
            echo "Updated password for: {$teacher->name} ({$teacher->email})\n";
        }
        
        echo "\nAll teacher passwords have been reset to: teacher123\n";
        echo "\nUpdated Teacher Login Credentials:\n";
        echo "====================================\n";
        
        foreach ($teachers as $teacher) {
            echo "- {$teacher->name}\n";
            echo "  Email: {$teacher->email}\n";
            echo "  Password: teacher123\n";
            echo "  Classroom: " . ($teacher->classroom ?? 'Not assigned') . "\n\n";
        }
    }
}
