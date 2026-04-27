<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('publish_date');
            $table->index(['status', 'visibility', 'created_at'], 'schools_status_visibility_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex('schools_status_visibility_created_at_index');
            $table->dropIndex(['publish_date']);
            $table->dropIndex(['created_at']);
        });
    }
};