<?php
// database/migrations/xxxx_xx_xx_create_pages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->string('name')->notNullable();
            $table->string('slug')->nullable();
            $table->json('structure')->notNullable(); // This will store your form data
            $table->timestamps(); // created_at and updated_at

            // Indexes
            $table->index('event_id');
            $table->index('slug');
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
