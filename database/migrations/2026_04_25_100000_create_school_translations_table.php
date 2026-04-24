<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('locale', 10);

            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('facilities')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_tagline')->nullable();
            $table->string('school_motto')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();

            $table->timestamps();

            $table->unique(['school_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_translations');
    }
};
