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
        Schema::create('first_aid_guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category'); // emergency, injury, illness, environmental, etc.
            $table->string('severity'); // critical, urgent, moderate, minor
            $table->json('symptoms')->nullable(); // Array of symptoms
            $table->json('steps'); // Array of step-by-step instructions
            $table->json('do_tips')->nullable(); // Array of what to do
            $table->json('dont_tips')->nullable(); // Array of what not to do
            $table->json('required_items')->nullable(); // Array of needed supplies
            $table->string('target_body_part')->nullable(); // head, chest, abdomen, etc.
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('region')->default('global'); // global, africa, asia, europe, americas
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('first_aid_guides');
    }
};
