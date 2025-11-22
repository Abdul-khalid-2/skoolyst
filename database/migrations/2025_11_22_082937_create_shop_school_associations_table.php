<?php
// database/migrations/2024_01_01_000005_create_shop_school_associations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_school_associations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');

            $table->enum('association_type', ['preferred', 'official', 'affiliated', 'general'])->default('general');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->unique(['shop_id', 'school_id']);
            $table->index(['school_id', 'is_active']);
            $table->index('association_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_school_associations');
    }
};
