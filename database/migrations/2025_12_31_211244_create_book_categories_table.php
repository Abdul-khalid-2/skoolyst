<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('book_categories')->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('cover_image')->nullable();

            $table->enum('book_type', [
                'academic',
                'competitive',
                'story',
                'religious',
                'general_knowledge',
                'magazine',
                'newspaper'
            ])->default('academic');

            $table->string('education_level')->nullable();
            $table->string('board')->nullable();

            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->index(['book_type', 'education_level']);
            $table->index('parent_id');
            $table->index('slug');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_categories');
    }
};
