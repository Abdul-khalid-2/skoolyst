<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mcqs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('test_type_id')->nullable()->constrained('test_types')->onDelete('set null');

            $table->longText('question');
            $table->string('question_type')->default('single');
            $table->json('options');
            $table->json('correct_answers');
            $table->text('explanation')->nullable();
            $table->text('hint')->nullable();

            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('time_limit_seconds')->nullable();
            $table->integer('marks')->default(1);
            $table->integer('negative_marks')->default(0);

            $table->json('tags')->nullable();
            $table->string('reference_book')->nullable();
            $table->string('reference_page')->nullable();

            $table->boolean('is_premium')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['topic_id', 'difficulty_level']);
            $table->index(['subject_id', 'status']);
            $table->index('is_premium');
            $table->index('created_by');
            $table->index('question_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcqs');
    }
};
