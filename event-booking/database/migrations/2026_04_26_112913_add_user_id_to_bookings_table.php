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
        Schema::table('bookings', function (Blueprint $table) {

            // Add user_id column ONLY if it doesn't exist
            if (!Schema::hasColumn('bookings', 'user_id')) {

                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id') // optional (position)
                    ->constrained('users') // explicitly reference users table
                    ->cascadeOnDelete();

            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            if (Schema::hasColumn('bookings', 'user_id')) {

                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');

            }

        });
    }
};