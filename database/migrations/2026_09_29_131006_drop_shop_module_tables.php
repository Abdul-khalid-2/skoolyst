<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Step 3 of the Shop/Blog/MCQ/Books removal: the Shop/Ecommerce module.
 * Must run AFTER drop_books_module_tables (books.product_id required a
 * products row) and AFTER drop_foreign_keys_before_module_removal
 * (videos.shop_id had to be cleared first).
 *
 * Dropped, in child-to-parent order:
 *   order_items, coupon_usage, coupon_applicables
 *   -> orders, shop_reviews, product_attributes
 *   -> products, shop_school_associations
 *   -> product_categories
 *   -> shops
 *   -> coupons
 *
 * (coupon_usage — singular — is the real table name; the
 * "create_coupon_usages_table" migration filename is misleading.)
 *
 * WARNING: this permanently deletes all data in these tables. There is no
 * way to recover it once this migration has run on a database — see down().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('coupon_applicables');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('shop_reviews');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('shop_school_associations');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('coupons');
    }

    public function down(): void
    {
        // Deliberately not implemented — see drop_books_module_tables.php.
        throw new \RuntimeException(
            'This migration permanently dropped the Shop/Ecommerce tables (shops, products, '
            . 'orders, coupons, etc.) and their data. There is no rollback — restore from a '
            . 'pre-migration database backup instead.'
        );
    }
};
