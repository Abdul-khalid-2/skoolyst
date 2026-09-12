<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('uuid');
        });

        // Populate slugs for existing schools
        $schools = DB::table('schools')->orderBy('id')->get(['id', 'name', 'uuid']);
        $usedSlugs = [];

        foreach ($schools as $school) {
            $baseSlug = Str::slug($school->name);

            // Fall back to uuid-prefix if name produces empty slug (e.g. non-Latin names)
            if ($baseSlug === '') {
                $baseSlug = 'school-' . substr($school->uuid, 0, 8);
            }

            $slug = $baseSlug;
            $counter = 2;
            while (in_array($slug, $usedSlugs)) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $usedSlugs[] = $slug;

            DB::table('schools')->where('id', $school->id)->update(['slug' => $slug]);
        }

        Schema::table('schools', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
