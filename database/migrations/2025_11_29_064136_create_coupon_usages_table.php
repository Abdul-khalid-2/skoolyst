<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->decimal('discount_amount', 10, 2);

            $table->timestamps();

            // Indexes
            $table->index(['coupon_id', 'user_id']);
            $table->index('order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_usage');
    }
};
