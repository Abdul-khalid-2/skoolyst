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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('restrict');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('association_id')->nullable()->constrained('shop_school_associations')->onDelete('cascade');

            $table->string('name');
            $table->string('slug');
            $table->string('sku')->unique()->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();

            $table->enum('product_type', ['book', 'copy', 'stationery', 'bag', 'uniform', 'other'])->default('other');

            // Common product attributes
            $table->string('brand')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();

            // Pricing
            $table->decimal('base_price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('profit_margin', 5, 2)->nullable();

            // Inventory
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('manage_stock')->default(true);
            $table->boolean('is_in_stock')->default(true);

            // Product status
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(false);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Media
            $table->string('main_image_url')->nullable();
            $table->json('image_gallery')->nullable();

            $table->timestamps();

            // Indexes
            $table->unique(['shop_id', 'slug']);
            $table->index('product_type');
            $table->index(['is_active', 'is_approved']);
            $table->index('base_price');
            $table->index(['is_in_stock', 'stock_quantity']);
            $table->index('school_id');
            $table->index('association_id');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
