<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');

            // Announcement details
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->string('feature_image')->nullable();

            // Metadata
            $table->integer('view_count')->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->dateTime('publish_at')->nullable();
            $table->dateTime('expire_at')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('school_id');
            $table->index('branch_id');
            $table->index('status');
            $table->index('publish_at');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
