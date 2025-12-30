<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->enum('fee_structure_type', ['fixed', 'class_wise'])->default('fixed');
            $table->text('class_wise_fees')->nullable()->after('discounted_fees');
        });
    }

    public function down()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['fee_structure_type', 'class_wise_fees']);
        });
    }
};
