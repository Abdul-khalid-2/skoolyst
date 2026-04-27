<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->index('status');
            $table->index('event_date');
            $table->index(['school_id', 'status']);
            $table->index(['branch_id', 'event_date'], 'events_branch_id_event_date_index');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['events_branch_id_event_date_index']);
            $table->dropIndex(['school_id', 'status']);
            $table->dropIndex(['event_date']);
            $table->dropIndex(['status']);
        });
    }
};
