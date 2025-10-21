<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->unique(['school_id', 'feature_id']);
            $table->index('priority');
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_feature');
    }
};
