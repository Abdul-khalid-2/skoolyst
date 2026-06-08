<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The Order model already references coupon_id, but the column was never
        // created. Add it now so applied coupons can be persisted on orders.
        if (! Schema::hasColumn('orders', 'coupon_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('coupon_id')
                    ->nullable()
                    ->after('discount_amount')
                    ->constrained('coupons')
                    ->nullOnDelete();
            });
        }

        // Guests can place orders (orders.user_id is nullable), so coupon usage
        // must allow a null user as well.
        Schema::table('coupon_usage', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('coupon_usage', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'coupon_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['coupon_id']);
                $table->dropColumn('coupon_id');
            });
        }

        Schema::table('coupon_usage', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('coupon_usage', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
