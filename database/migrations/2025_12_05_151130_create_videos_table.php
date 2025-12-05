<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('parent_id')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Foreign keys
            $table->foreignId('category_id')->nullable()->constrained('video_categories')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');

            // Video details
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('embed_video_link');
            $table->string('thumbnail')->nullable();
            $table->string('video_id')->nullable(); // YouTube video ID extracted from link
            $table->integer('sort_order')->default(0);

            // Metadata
            $table->integer('views')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->enum('status', ['draft', 'published', 'private'])->default('published');

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'published_at']);
            $table->index('is_featured');
            $table->index('is_approved');
            $table->index(['school_id', 'status']);
            $table->index(['shop_id', 'status']);
            $table->index('category_id');
        });

        Schema::create('video_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('reaction', ['like', 'love', 'haha', 'wow', 'sad', 'angry'])->default('like');
            $table->timestamps();

            // Unique constraint
            $table->unique(['video_id', 'user_id']);

            // Indexes
            $table->index(['video_id', 'reaction']);
            $table->index('user_id');
        });

        Schema::create('video_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('video_comments')->onDelete('cascade');

            // Comment details
            $table->text('message');
            $table->string('name')->nullable(); // For guest users
            $table->string('email')->nullable(); // For guest users

            // Status
            $table->boolean('is_approved')->default(true);
            $table->integer('likes_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['video_id', 'is_approved']);
            $table->index('parent_id');
            $table->index('user_id');
        });

        Schema::create('video_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('video_comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Unique constraint
            $table->unique(['comment_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_comment_likes');
        Schema::dropIfExists('video_comments');
        Schema::dropIfExists('video_reactions');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('video_categories');
    }
};
