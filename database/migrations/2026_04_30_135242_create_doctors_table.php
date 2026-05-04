<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('whatsapp')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth');
            $table->string('national_id')->unique();
            $table->string('medical_license_number')->unique();
            $table->string('specialty');
            $table->text('qualifications');
            $table->string('hospital_clinic');
            $table->string('province');
            $table->string('district');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['pending', 'verified', 'active', 'inactive'])->default('pending');
            $table->boolean('is_available')->default(true);
            $table->json('working_hours')->nullable();
            $table->string('consultation_fee')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->json('languages_spoken')->nullable();
            $table->integer('years_of_experience');
            $table->date('license_expiry');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
