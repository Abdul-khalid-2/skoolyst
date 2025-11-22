<?php
// database/migrations/2024_01_01_000004_create_product_attributes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->enum('attribute_type', ['book', 'copy', 'bag', 'uniform'])->default('book');

            // Book specific attributes
            $table->string('book_author')->nullable();
            $table->string('book_publisher')->nullable();
            $table->string('book_isbn', 20)->nullable();
            $table->string('book_edition', 50)->nullable();
            $table->year('book_publication_year')->nullable();
            $table->enum('education_board', [
                'federal',
                'punjab',
                'sindh',
                'kpk',
                'balochistan',
                'ajk',
                'cambridge',
                'ib',
                'other'
            ])->nullable();
            $table->string('school_name')->nullable();
            $table->string('class_level', 50)->nullable(); // e.g., "Class 1", "Grade 5", "O-Levels"
            $table->string('subject', 100)->nullable();
            $table->enum('medium', ['english', 'urdu', 'bilingual', 'other'])->nullable();

            // Copy specific attributes
            $table->integer('copy_pages')->nullable();
            $table->enum('copy_quality', ['premium', 'standard', 'economy'])->nullable();
            $table->enum('copy_size', ['a4', 'a5', 'quarter', 'half', 'other'])->nullable();
            $table->enum('copy_type', ['single_line', 'double_line', 'four_line', 'plain', 'graph'])->nullable();

            // Bag specific attributes
            $table->enum('bag_type', ['school_bag', 'backpack', 'satchel', 'lunch_bag', 'other'])->nullable();
            $table->integer('bag_compartments')->nullable();
            $table->boolean('bag_water_resistant')->default(false);
            $table->boolean('bag_wheels')->default(false);

            // Common measurements
            $table->decimal('weight_grams', 8, 2)->nullable();
            $table->string('dimensions', 100)->nullable(); // "LxWxH" format

            $table->timestamps();

            // Indexes
            $table->index('attribute_type');
            $table->index(['education_board', 'class_level']);
            $table->index(['book_author', 'book_publisher']);
            $table->index('class_level');
            $table->index('subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
