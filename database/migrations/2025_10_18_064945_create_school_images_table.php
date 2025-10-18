<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');

            $table->string('image_path');   // store file path
            $table->string('title')->nullable(); // image title/caption
            $table->timestamps();
            $table->index('school_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_images');
    }
};
