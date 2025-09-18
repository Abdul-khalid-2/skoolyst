<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    // database/migrations/xxxx_xx_xx_create_branches_table.php
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name'); // Branch name (could be same as main school or different)
            $table->string('address');
            $table->string('city');
            $table->string('contact_number')->nullable();
            $table->string('branch_head_name')->nullable(); // Principal/Head of branch
            $table->decimal('latitude', 10, 8)->nullable(); // For maps
            $table->decimal('longitude', 11, 8)->nullable(); // For maps
            $table->boolean('is_main_branch')->default(false); // Flag for main branch
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Indexes
            $table->index('school_id');
            $table->index('city');
            $table->index('is_main_branch');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
