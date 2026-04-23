<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->decimal('total_tracked_read_minutes', 16, 5)->default(0)->after('view_count');
        });

        if (Schema::hasColumn('blog_posts', 'total_tracked_read_seconds')) {
            DB::statement('UPDATE blog_posts SET total_tracked_read_minutes = total_tracked_read_seconds / 60.0');
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->dropColumn('total_tracked_read_seconds');
            });
        }
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('total_tracked_read_minutes');
        });
    }
};
