<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class L4SWDStudentsSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Alice Johnson', 'email' => 'alice.j@school.com'],
            ['name' => 'Bob Smith', 'email' => 'bob.s@school.com'],
            ['name' => 'Carol Williams', 'email' => 'carol.w@school.com'],
            ['name' => 'David Brown', 'email' => 'david.b@school.com'],
            ['name' => 'Emma Davis', 'email' => 'emma.d@school.com'],
            ['name' => 'Frank Miller', 'email' => 'frank.m@school.com'],
            ['name' => 'Grace Wilson', 'email' => 'grace.w@school.com'],
            ['name' => 'Henry Moore', 'email' => 'henry.m@school.com'],
            ['name' => 'Iris Taylor', 'email' => 'iris.t@school.com'],
            ['name' => 'Jack Anderson', 'email' => 'jack.a@school.com'],
            ['name' => 'Kate Thomas', 'email' => 'kate.t@school.com'],
            ['name' => 'Liam Jackson', 'email' => 'liam.j@school.com'],
            ['name' => 'Mia White', 'email' => 'mia.w@school.com'],
            ['name' => 'Noah Harris', 'email' => 'noah.h@school.com'],
            ['name' => 'Olivia Martin', 'email' => 'olivia.m@school.com'],
        ];

        foreach ($students as $student) {
            User::firstOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'classroom' => 'L4SWD',
                ]
            );
        }
    }
}
