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
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('national_id')->nullable()->change();
            $table->string('medical_license_number')->nullable()->change();
            $table->text('qualifications')->nullable()->change();
            $table->integer('years_of_experience')->nullable()->change();
            $table->date('license_expiry')->nullable()->change();
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
            $table->string('consultation_fee')->nullable()->change();
            $table->text('bio')->nullable()->change();
            $table->json('languages_spoken')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
