<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category')->default('general');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('features');
    }
};
