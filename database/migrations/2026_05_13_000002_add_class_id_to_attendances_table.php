<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable()->after('teacher_id');
        });

        // Populate class_id based on existing classroom name (PostgreSQL compatible)
        DB::statement('
            UPDATE attendances
            SET class_id = school_classes.id
            FROM school_classes
            WHERE school_classes.name = attendances.classroom
            AND attendances.classroom IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('class_id');
        });
    }
};
