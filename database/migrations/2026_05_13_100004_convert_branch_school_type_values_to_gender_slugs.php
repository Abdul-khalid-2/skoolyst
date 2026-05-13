<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE branches MODIFY school_type VARCHAR(50) NULL');
        }

        $map = [
            'Co-Ed' => 'co-education',
            'Boys' => 'boys',
            'Girls' => 'girls',
        ];

        foreach ($map as $from => $to) {
            DB::table('branches')->where('school_type', $from)->update(['school_type' => $to]);
        }
    }

    public function down(): void
    {
        $map = [
            'co-education' => 'Co-Ed',
            'boys' => 'Boys',
            'girls' => 'Girls',
        ];

        foreach ($map as $from => $to) {
            DB::table('branches')->where('school_type', $from)->update(['school_type' => $to]);
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE branches MODIFY school_type ENUM('Co-Ed','Boys','Girls') NULL");
        }
    }
};
