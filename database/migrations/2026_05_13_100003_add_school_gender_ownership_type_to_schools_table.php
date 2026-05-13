<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('school_gender_type', 50)->nullable()->after('facilities');
            $table->string('school_ownership_type', 50)->nullable()->after('school_gender_type');
        });

        $genderMap = [
            'Co-Ed' => 'co-education',
            'Boys' => 'boys',
            'Girls' => 'girls',
        ];

        foreach ($genderMap as $from => $to) {
            DB::table('schools')->where('school_type', $from)->update([
                'school_gender_type' => $to,
                'school_ownership_type' => 'private',
            ]);
        }

        DB::table('schools')->whereNull('school_gender_type')->update([
            'school_gender_type' => 'co-education',
            'school_ownership_type' => 'private',
        ]);

        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex(['school_type']);
            $table->dropColumn('school_type');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->index('school_gender_type');
            $table->index('school_ownership_type');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex(['school_gender_type']);
            $table->dropIndex(['school_ownership_type']);
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->enum('school_type', ['Co-Ed', 'Boys', 'Girls'])->default('Co-Ed')->after('facilities');
        });

        $revMap = [
            'co-education' => 'Co-Ed',
            'boys' => 'Boys',
            'girls' => 'Girls',
        ];

        foreach ($revMap as $from => $to) {
            DB::table('schools')->where('school_gender_type', $from)->update(['school_type' => $to]);
        }

        DB::table('schools')->whereNull('school_type')->update(['school_type' => 'Co-Ed']);

        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['school_gender_type', 'school_ownership_type']);
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->index('school_type');
        });
    }
};
