<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_categories', function (Blueprint $table) {
            $table->index('status');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('video_categories', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['status']);
        });
    }
};
