<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Step 1 of the Shop/Blog/MCQ/Books removal: clear the two foreign keys that
 * point FROM a table we are KEEPING INTO a table we are about to drop, so the
 * later DROP TABLE statements don't fail with a "table is referenced by a
 * foreign key" error.
 *
 * - videos.shop_id -> shops.id : the "assign a shop to this video" feature is
 *   being removed entirely, so the column itself is dropped (not just the FK).
 * - study_materials.subject_id -> subjects.id and
 *   study_materials.test_type_id -> test_types.id : Study Materials itself is
 *   NOT being removed, so only the FK constraints are dropped — the columns
 *   stay (now plain, unconstrained nullable integers) in case the optional
 *   MCQ-taxonomy tagging is reintroduced later.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('videos', 'shop_id')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            });
        }

        if (Schema::hasTable('study_materials')) {
            Schema::table('study_materials', function (Blueprint $table) {
                if (Schema::hasColumn('study_materials', 'subject_id')) {
                    $table->dropForeign(['subject_id']);
                }
                if (Schema::hasColumn('study_materials', 'test_type_id')) {
                    $table->dropForeign(['test_type_id']);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('study_materials')) {
            Schema::table('study_materials', function (Blueprint $table) {
                if (Schema::hasColumn('study_materials', 'subject_id')) {
                    $table->foreign('subject_id')->references('id')->on('subjects')->nullOnDelete();
                }
                if (Schema::hasColumn('study_materials', 'test_type_id')) {
                    $table->foreign('test_type_id')->references('id')->on('test_types')->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('videos') && !Schema::hasColumn('videos', 'shop_id')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->foreignId('shop_id')->nullable()->after('school_id')->constrained()->onDelete('cascade');
            });
        }
    }
};
