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
            $table->id(); // Primary key
            $table->string('name'); // School name
            $table->text('description'); // School description
            $table->string('address'); // School address
            $table->string('city'); // City
            $table->string('contact_number')->nullable(); // School contact number
            $table->string('email')->nullable(); // School email
            $table->string('website')->nullable(); // School website URL
            $table->text('facilities')->nullable(); // Facilities available in the school
            $table->enum('school_type', ['Co-Ed', 'Boys', 'Girls'])->default('Co-Ed'); // School type
            $table->timestamps(); // Created at, updated at
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
