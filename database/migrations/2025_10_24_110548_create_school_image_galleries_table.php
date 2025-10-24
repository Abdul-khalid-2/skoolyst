<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_image_galleries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('image_name');
            $table->string('image_unique_name')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['school_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_image_galleries');
    }
};
