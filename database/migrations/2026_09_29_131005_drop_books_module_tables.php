<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Step 2 of the Shop/Blog/MCQ/Books removal: the Books ("Study Resources")
 * admin feature and its tables. Must run BEFORE the Shop tables migration —
 * books.product_id is a required (non-nullable) foreign key to products.id,
 * so books has to go first.
 *
 * Dropped, in child-to-parent order:
 *   book_reviews -> books -> book_categories
 *
 * WARNING: this permanently deletes all data in these tables. There is no
 * way to recover it once this migration has run on a database — see down().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('book_reviews');
        Schema::dropIfExists('books');
        Schema::dropIfExists('book_categories');
    }

    public function down(): void
    {
        // Deliberately not implemented: this migration destroys data, and a
        // schema-only recreation of empty tables would misleadingly look like
        // a successful rollback. If you need these tables back, restore from
        // a database backup taken before this migration ran.
        throw new \RuntimeException(
            'This migration permanently dropped book_reviews/books/book_categories and their data. '
            . 'There is no rollback — restore from a pre-migration database backup instead.'
        );
    }
};
