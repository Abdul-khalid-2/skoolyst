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
        Schema::table('branches', function (Blueprint $table) {
            // Add new nullable fields
            $table->text('description')->nullable()->after('branch_head_name');
            $table->enum('school_type', ['Co-Ed', 'Boys', 'Girls'])->nullable()->after('description');
            $table->text('fee_structure')->nullable()->after('school_type');
            $table->text('curriculums')->nullable()->after('fee_structure');
            $table->text('classes')->nullable()->after('curriculums');
            $table->json('images_gallery')->nullable()->after('classes');

            // Add JSON field for features (since you have features table)
            $table->json('features')->nullable()->after('images_gallery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'school_type',
                'fee_structure',
                'curriculums',
                'classes',
                'images_gallery',
                'features'
            ]);
        });
    }
};
