<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relationships
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');

            // Product snapshot at time of order (in case product details change later)
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();
            $table->json('product_attributes')->nullable(); // Store product attributes as JSON

            // Pricing
            $table->decimal('unit_price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->integer('quantity');
            $table->decimal('total_price', 12, 2);

            // Product category information
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->string('category_name')->nullable();

            // School association (if any)
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('association_id')->nullable()->constrained('shop_school_associations')->onDelete('set null');

            // Item status
            $table->enum('status', [
                'pending',
                'confirmed',
                'shipped',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');

            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('product_id');
            $table->index('shop_id');
            $table->index('category_id');
            $table->index('school_id');
            $table->index('status');
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
