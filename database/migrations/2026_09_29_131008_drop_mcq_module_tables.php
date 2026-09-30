<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Step 5 of the Shop/Blog/MCQ/Books removal: the MCQ/Quiz module.
 * Must run AFTER drop_foreign_keys_before_module_removal (study_materials'
 * FKs into subjects/test_types had to be cleared first — study_materials
 * itself is kept).
 *
 * Dropped, in child-to-parent order:
 *   subject_test_type, mcq_test_type (pivot tables)
 *   -> user_progress, user_mcq_answers
 *   -> user_test_attempts
 *   -> mock_test_questions
 *   -> mock_tests
 *   -> mcqs
 *   -> topics
 *   -> subjects
 *   -> test_types
 *
 * WARNING: this permanently deletes all data in these tables — including any
 * user test-attempt history — with no way to recover it once this migration
 * has run on a database. See down().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('subject_test_type');
        Schema::dropIfExists('mcq_test_type');
        Schema::dropIfExists('user_progress');
        Schema::dropIfExists('user_mcq_answers');
        Schema::dropIfExists('user_test_attempts');
        Schema::dropIfExists('mock_test_questions');
        Schema::dropIfExists('mock_tests');
        Schema::dropIfExists('mcqs');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('test_types');
    }

    public function down(): void
    {
        // Deliberately not implemented — see drop_books_module_tables.php.
        throw new \RuntimeException(
            'This migration permanently dropped the MCQ/Quiz tables (mcqs, subjects, topics, '
            . 'test_types, mock_tests, user_test_attempts, etc.) and their data. There is no '
            . 'rollback — restore from a pre-migration database backup instead.'
        );
    }
};
