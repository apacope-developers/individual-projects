<?php

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');

it('allows a teacher to mark attendance', function () {
    $teacher = User::factory()->create(['role' => 'teacher']);
    $student = User::factory()->create(['role' => 'student', 'classroom' => '5A']);

    $this->actingAs($teacher)
        ->post(route('attendance.store'), [
            'date' => now()->toDateString(),
            'classroom' => '5A',
            'statuses' => [ $student->id => 'present' ],
        ])
        ->assertRedirect(route('attendance.index', ['date' => now()->toDateString(), 'classroom' => '5A']));

    $this->assertDatabaseHas('attendances', [
        'student_id' => $student->id,
        'status' => 'present',
    ]);
});

it('allows user registration', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'student',
        'classroom' => '5A',
    ];

    $this->post(route('register'), $userData)
        ->assertRedirect(route('attendance.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'student',
        'classroom' => '5A',
    ]);

    $this->assertAuthenticated();
});
