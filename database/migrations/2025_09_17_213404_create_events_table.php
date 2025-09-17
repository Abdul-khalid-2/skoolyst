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
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade'); // Foreign key for schools
            $table->string('event_name'); // Event name
            $table->text('event_description'); // Event description
            $table->date('event_date'); // Event date
            $table->string('event_location'); // Event location
            $table->timestamps(); // Created at, updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
