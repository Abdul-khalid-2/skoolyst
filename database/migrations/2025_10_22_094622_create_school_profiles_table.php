<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');

            // Quick Facts (JSON format for flexibility)
            $table->json('quick_facts')->nullable();

            // School Logo
            $table->string('logo')->nullable();

            // Social Media Links (JSON format)
            $table->json('social_media')->nullable();

            // Analytics
            $table->integer('visitor_count')->default(0);
            $table->bigInteger('total_time_spent')->default(0); // in seconds
            $table->timestamp('last_visited_at')->nullable();

            // Additional profile fields you might need
            $table->string('established_year')->nullable();
            $table->integer('student_strength')->nullable();
            $table->integer('faculty_count')->nullable();
            $table->string('campus_size')->nullable();
            $table->string('school_motto')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();

            $table->timestamps();

            // Indexes
            $table->unique('school_id');
            $table->index('visitor_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
