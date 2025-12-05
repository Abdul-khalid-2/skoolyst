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
        Schema::table('orders', function (Blueprint $table) {
            // First, make user_id nullable to allow guest orders
            $table->foreignId('user_id')->nullable()->change();

            // Add user information fields for guest orders
            $table->string('name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');

            // Remove the cascade delete constraint since user_id is now nullable
            // We need to drop and recreate the foreign key
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['name', 'email', 'phone', 'address']);

            // Restore user_id to not nullable
            // First drop the foreign key
            $table->dropForeign(['user_id']);

            // Change back to not nullable
            $table->foreignId('user_id')->nullable(false)->change();

            // Restore the cascade delete constraint
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
