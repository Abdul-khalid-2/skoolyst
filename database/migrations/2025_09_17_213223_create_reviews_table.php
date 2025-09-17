<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In your migration file: database/migrations/xxxx_xx_xx_create_reviews_table.php
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade'); // Foreign key for schools
            $table->text('review'); // Review content
            $table->integer('rating')->default(0); // Rating (1-5)
            $table->string('reviewer_name'); // Reviewer (Parent/Student)
            $table->timestamps(); // Created at, updated at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
