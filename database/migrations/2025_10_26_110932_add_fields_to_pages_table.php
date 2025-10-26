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
        Schema::table('pages', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('feature_image')->nullable()->after('description');
            $table->date('start_date')->nullable()->after('feature_image');
            $table->date('end_date')->nullable()->after('start_date');
            $table->enum('status', ['active', 'pending', 'inactive'])->default('pending')->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['description', 'feature_image', 'start_date', 'end_date', 'status']);
        });
    }
};
