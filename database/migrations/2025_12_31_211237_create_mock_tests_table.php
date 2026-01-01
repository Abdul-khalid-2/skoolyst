<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('test_type_id')->constrained('test_types')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();

            $table->integer('total_questions');
            $table->integer('total_marks');
            $table->integer('total_time_minutes');
            $table->integer('passing_marks')->default(33);

            $table->enum('test_mode', ['practice', 'timed', 'exam'])->default('practice');
            $table->boolean('shuffle_questions')->default(true);
            $table->boolean('show_result_immediately')->default(true);
            $table->boolean('allow_retake')->default(true);
            $table->integer('max_attempts')->nullable();

            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->json('subject_breakdown')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['test_type_id', 'status']);
            $table->index('is_free');
            $table->index('slug');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_tests');
    }
};
