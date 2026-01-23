<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mcq_test_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcq_id')->constrained('mcqs')->onDelete('cascade');
            $table->foreignId('test_type_id')->constrained('test_types')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['mcq_id', 'test_type_id']);
        });

        // Remove the foreign key constraint and column from mcqs table
        Schema::table('mcqs', function (Blueprint $table) {
            $table->dropForeign(['test_type_id']);
            $table->dropColumn('test_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('mcqs', function (Blueprint $table) {
            $table->foreignId('test_type_id')->nullable()->constrained('test_types')->onDelete('set null');
        });

        Schema::dropIfExists('mcq_test_type');
    }
};
