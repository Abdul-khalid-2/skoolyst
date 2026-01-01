<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('book_category_id')->nullable()->constrained('book_categories')->onDelete('set null');

            $table->string('author');
            $table->string('publisher');
            $table->string('isbn')->unique()->nullable();
            $table->string('edition');
            $table->year('publication_year');

            $table->enum('language', ['english', 'urdu', 'sindhi', 'pashto', 'other'])->default('english');
            $table->integer('pages')->nullable();
            $table->string('cover_type')->nullable();

            $table->string('education_board')->nullable();
            $table->string('class_level')->nullable();
            $table->string('subject')->nullable();

            $table->text('table_of_contents')->nullable();
            $table->text('sample_pages')->nullable();
            $table->string('preview_link')->nullable();

            $table->enum('book_condition', ['new', 'like_new', 'good', 'fair', 'poor'])->default('new');
            $table->text('condition_description')->nullable();

            $table->boolean('is_digital')->default(false);
            $table->string('digital_file')->nullable();
            $table->string('file_format')->nullable();
            $table->integer('file_size_mb')->nullable();

            $table->timestamps();

            $table->index(['author', 'publisher']);
            $table->index(['education_board', 'class_level', 'subject']);
            $table->index('isbn');
            $table->index('is_digital');
            $table->index('book_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
