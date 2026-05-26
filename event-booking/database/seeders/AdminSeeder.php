<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kirengamaxime0@gmail.com'],
            [
                'name' => 'kirenga maxime',
                'password' => bcrypt('Moonlanding@12'),
                'role' => 'admin',
            ]
        );
    }
}