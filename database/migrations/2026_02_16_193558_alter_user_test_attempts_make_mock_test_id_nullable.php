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
        Schema::table('user_test_attempts', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['mock_test_id']);
            
            // Modify the column to be nullable
            $table->foreignId('mock_test_id')->nullable()->change();
            
            // Re-add the foreign key constraint with null on delete
            $table->foreign('mock_test_id')->references('id')->on('mock_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_test_attempts', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['mock_test_id']);
            
            // Change back to not nullable
            $table->foreignId('mock_test_id')->nullable(false)->change();
            
            // Re-add the original foreign key
            $table->foreign('mock_test_id')->references('id')->on('mock_tests')->onDelete('cascade');
        });
    }
};