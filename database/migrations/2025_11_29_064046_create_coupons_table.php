<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Basic coupon info
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            // Discount details
            $table->enum('discount_type', ['percentage', 'fixed_amount', 'free_shipping'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('minimum_order_amount', 10, 2)->nullable();
            $table->decimal('maximum_discount_amount', 10, 2)->nullable();

            // Usage limits
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('usage_per_customer')->nullable();

            // Validity
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();

            // Scope
            $table->enum('scope', ['global', 'shop_specific', 'school_specific', 'product_specific'])->default('global');

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index(['is_active', 'valid_until']);
            $table->index('scope');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
