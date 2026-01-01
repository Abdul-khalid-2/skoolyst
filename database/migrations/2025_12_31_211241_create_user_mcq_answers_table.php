<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_mcq_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mcq_id')->constrained('mcqs')->onDelete('cascade');
            $table->foreignId('test_attempt_id')->nullable()->constrained('user_test_attempts')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('set null');

            $table->json('selected_answers');
            $table->boolean('is_correct')->default(false);
            $table->integer('time_taken_seconds')->default(0);
            $table->timestamp('answered_at')->useCurrent();

            $table->timestamps();

            $table->unique(['user_id', 'mcq_id', 'test_attempt_id']);
            $table->index(['user_id', 'topic_id', 'is_correct']);
            $table->index('answered_at');
            $table->index(['user_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_mcq_answers');
    }
};
