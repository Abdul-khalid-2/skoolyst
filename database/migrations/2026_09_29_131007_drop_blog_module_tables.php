<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Step 4 of the Shop/Blog/MCQ/Books removal: the Blog module.
 *
 * Dropped, in child-to-parent order:
 *   comments (blog post comments) -> blog_posts -> blog_categories
 *
 * WARNING: this permanently deletes all data in these tables. There is no
 * way to recover it once this migration has run on a database — see down().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
    }

    public function down(): void
    {
        // Deliberately not implemented — see drop_books_module_tables.php.
        throw new \RuntimeException(
            'This migration permanently dropped comments/blog_posts/blog_categories and their '
            . 'data. There is no rollback — restore from a pre-migration database backup instead.'
        );
    }
};
