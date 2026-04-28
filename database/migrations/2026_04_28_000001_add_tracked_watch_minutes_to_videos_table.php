<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('videos', 'total_tracked_watch_minutes')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->decimal('total_tracked_watch_minutes', 16, 5)->default(0)->after('views');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('videos', 'total_tracked_watch_minutes')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropColumn('total_tracked_watch_minutes');
            });
        }
    }
};
