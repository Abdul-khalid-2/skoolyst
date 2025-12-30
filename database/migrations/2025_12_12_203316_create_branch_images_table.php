<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_images', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('image_path');
            $table->string('image_name');
            $table->string('image_unique_name')->unique();
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->enum('type', ['gallery', 'banner', 'infrastructure', 'events', 'classroom'])->default('gallery');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_main_banner')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Indexes
            $table->index(['branch_id', 'status']);
            $table->index('type');
            $table->index('is_featured');
            $table->index('is_main_banner');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_images');
    }
};
