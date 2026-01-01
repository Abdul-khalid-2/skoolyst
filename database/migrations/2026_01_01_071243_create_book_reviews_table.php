<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->integer('rating')->default(5);
            $table->text('review');
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();

            $table->enum('review_type', ['academic', 'competitive', 'general'])->default('general');
            $table->string('reviewer_role')->nullable();

            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_helpful')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->integer('unhelpful_count')->default(0);

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

            // Indexes
            $table->index(['book_id', 'rating']);
            $table->index(['user_id', 'is_verified_purchase']);
            $table->index('status');
            $table->index('review_type');
            $table->index('is_verified_purchase');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_reviews');
    }
};
