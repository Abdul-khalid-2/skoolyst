<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // User and Shop relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');

            // Order information
            $table->string('order_number')->unique();
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded',
                'failed'
            ])->default('pending');

            // Pricing breakdown
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            // Payment information
            $table->enum('payment_method', [
                'credit_card',
                'debit_card',
                'cash_on_delivery',
                'digital_wallet',
                'bank_transfer'
            ])->default('cash_on_delivery');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
                'partially_refunded'
            ])->default('pending');

            $table->string('transaction_id')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();

            // Shipping information
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_email');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_zip_code');
            $table->string('shipping_country')->default('Pakistan');
            $table->text('delivery_instructions')->nullable();

            // Billing information (can be same as shipping)
            $table->boolean('billing_same_as_shipping')->default(true);
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zip_code')->nullable();
            $table->string('billing_country')->nullable();

            // Order metadata
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('metadata')->nullable(); // For additional custom data

            // Timestamps for order lifecycle
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Estimated delivery
            $table->timestamp('estimated_delivery_date')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('order_number');
            $table->index('user_id');
            $table->index('shop_id');
            $table->index('school_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
            $table->index(['shop_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
