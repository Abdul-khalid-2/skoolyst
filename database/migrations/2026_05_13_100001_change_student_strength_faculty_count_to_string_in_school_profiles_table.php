<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->string('student_strength', 50)->nullable()->change();
            $table->string('faculty_count', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->integer('student_strength')->nullable()->change();
            $table->integer('faculty_count')->nullable()->change();
        });
    }
};
