<?php
// database/migrations/2024_01_01_000001_create_shops_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Pakistan');

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->enum('shop_type', ['stationery', 'book_store', 'mixed', 'school_affiliated'])->default('mixed');
            $table->text('description')->nullable();

            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();

            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['city', 'state']);
            $table->index('shop_type');
            $table->index(['is_verified', 'is_active']);
            $table->index('school_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
