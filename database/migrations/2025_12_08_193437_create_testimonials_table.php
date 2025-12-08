<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // User relationship (if logged in)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Testimonial content
            $table->text('message')->nullable();
            $table->integer('rating')->default(5); // Platform rating (1-5)

            // Author information
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->string('author_location')->nullable(); // e.g., Karachi
            $table->string('author_role')->default('Parent'); // Parent, Student, etc.

            // Media
            $table->string('avatar')->nullable(); // Custom avatar image

            // Status management
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->boolean('featured')->default(false);
            $table->boolean('show_on_homepage')->default(false);

            // Platform feedback specific fields
            $table->json('platform_features_liked')->nullable(); // Store as JSON array
            $table->enum('experience_rating', ['excellent', 'good', 'average', 'poor'])->default('good');

            // Timestamps
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();

            // Indexes
            $table->index('status');
            $table->index('featured');
            $table->index('show_on_homepage');
            $table->index('rating');
            $table->index(['status', 'featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
