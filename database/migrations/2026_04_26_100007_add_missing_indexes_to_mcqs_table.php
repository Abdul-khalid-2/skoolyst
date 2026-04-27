<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mcqs', function (Blueprint $table) {
            $table->index('status');
            $table->index('approved_by');
            $table->index('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('mcqs', function (Blueprint $table) {
            $table->dropIndex(['approved_at']);
            $table->dropIndex(['approved_by']);
            $table->dropIndex(['status']);
        });
    }
};
