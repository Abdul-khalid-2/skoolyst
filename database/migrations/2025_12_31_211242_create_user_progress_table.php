<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');

            $table->enum('progress_type', ['topic_read', 'mcq_practice', 'test_completed']);
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->integer('total_items')->default(0);
            $table->integer('completed_items')->default(0);

            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->json('progress_data')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'topic_id', 'progress_type']);
            $table->index(['user_id', 'subject_id']);
            $table->index('last_accessed_at');
            $table->index('progress_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
