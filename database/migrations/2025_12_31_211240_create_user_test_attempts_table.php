<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_test_attempts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mock_test_id')->constrained('mock_tests')->onDelete('cascade');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->integer('total_questions')->default(0);
            $table->integer('attempted_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            $table->integer('skipped_questions')->default(0);

            $table->integer('total_marks_obtained')->default(0);
            $table->integer('total_possible_marks')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->enum('result_status', ['passed', 'failed', 'pending'])->default('pending');

            $table->integer('time_taken_seconds')->default(0);
            $table->integer('remaining_time_seconds')->default(0);

            $table->json('answers_data')->nullable();
            $table->json('question_analysis')->nullable();

            $table->enum('status', ['in_progress', 'completed', 'expired', 'abandoned'])->default('in_progress');

            $table->timestamps();

            $table->index(['user_id', 'mock_test_id']);
            $table->index(['completed_at', 'result_status']);
            $table->index('percentage');
            $table->index('status');
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_test_attempts');
    }
};
