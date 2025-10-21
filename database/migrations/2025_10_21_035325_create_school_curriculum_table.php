<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_curriculum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('curriculum_id')->constrained('curriculums')->onDelete('cascade'); // Explicitly specify table name
            $table->timestamps();

            $table->unique(['school_id', 'curriculum_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_curriculum');
    }
};
