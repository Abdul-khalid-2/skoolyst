<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            // Add new columns for individual element types
            $table->json('title')->nullable()->after('structure');
            $table->json('banner')->nullable()->after('title');
            $table->json('image')->nullable()->after('banner');
            $table->json('rich_text')->nullable()->after('image');
            $table->json('text_left_image_right')->nullable()->after('rich_text');
            $table->json('custom_html')->nullable()->after('text_left_image_right');

            // You can also add a general elements column to store all canvas elements
            $table->json('canvas_elements')->nullable()->after('custom_html');
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'banner',
                'image',
                'rich_text',
                'text_left_image_right',
                'custom_html',
                'canvas_elements'
            ]);
        });
    }
};
