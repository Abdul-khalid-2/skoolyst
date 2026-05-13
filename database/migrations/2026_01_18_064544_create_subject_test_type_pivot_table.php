<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subject_test_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('test_type_id')->constrained('test_types')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['subject_id', 'test_type_id']);
        });

        // Migrate existing relationships
        // If you have data in the subjects.test_type_id column
        // Use CURRENT_TIMESTAMP so SQLite (PHPUnit) and MySQL both accept this during migrate.
        DB::statement('INSERT INTO subject_test_type (subject_id, test_type_id, created_at, updated_at)
                      SELECT id, test_type_id, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP FROM subjects WHERE test_type_id IS NOT NULL');
    }

    public function down()
    {
        Schema::dropIfExists('subject_test_type');
    }
};
