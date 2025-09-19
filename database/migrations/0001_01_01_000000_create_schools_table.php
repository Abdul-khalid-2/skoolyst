<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id(); // Auto-increment PK (hidden from outside)
            $table->uuid('uuid')->unique(); // Public-facing identifier

            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('website')->nullable();

            $table->text('description')->nullable();
            $table->text('facilities')->nullable();

            $table->enum('school_type', ['Co-Ed', 'Boys', 'Girls'])->default('Co-Ed');

            // 💰 Fees
            $table->decimal('regular_fees', 10, 2)->nullable();
            $table->decimal('discounted_fees', 10, 2)->nullable();
            $table->decimal('admission_fees', 10, 2)->nullable();

            // 📌 Other fields
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->timestamp('publish_date')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('city');
            $table->index('school_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
