<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_test_id')->constrained('mock_tests')->onDelete('cascade');
            $table->foreignId('mcq_id')->constrained('mcqs')->onDelete('cascade');
            $table->integer('question_number');
            $table->integer('marks')->default(1);
            $table->integer('negative_marks')->default(0);
            $table->integer('time_limit_seconds')->nullable();
            $table->timestamps();

            $table->unique(['mock_test_id', 'mcq_id']);
            $table->index('question_number');
            $table->index(['mock_test_id', 'question_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_test_questions');
    }
};
