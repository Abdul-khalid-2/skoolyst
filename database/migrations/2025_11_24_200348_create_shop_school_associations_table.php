<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_school_associations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('association_type', ['preferred', 'official', 'affiliated', 'general'])->default('general');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);

            // Additional fields for better management
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');

            // Permissions for this specific association
            $table->boolean('can_add_products')->default(true);
            $table->boolean('can_manage_products')->default(true);
            $table->boolean('can_view_analytics')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->unique(['shop_id', 'school_id']);
            $table->index(['school_id', 'is_active']);
            $table->index('association_type');
            $table->index('status');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_school_associations');
    }
};
