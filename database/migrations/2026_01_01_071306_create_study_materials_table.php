<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('test_type_id')->nullable()->constrained('test_types')->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('material_type', ['notes', 'past_papers', 'formula_sheet', 'summary', 'other']);
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size')->nullable();
            $table->integer('download_count')->default(0);
            $table->integer('page_count')->nullable();

            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->json('tags')->nullable();
            $table->integer('view_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['subject_id', 'material_type']);
            $table->index(['is_free', 'status']);
            $table->index('user_id');
            $table->index('test_type_id');
            $table->index('material_type');
            $table->index('download_count');
            $table->index('view_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_materials');
    }
};
