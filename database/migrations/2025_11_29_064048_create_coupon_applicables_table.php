<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupon_applicables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');

            // Polymorphic relation
            $table->morphs('applicable'); // creates applicable_id + applicable_type

            $table->timestamps();

            // Indexes
            $table->unique(
                ['coupon_id', 'applicable_id', 'applicable_type'],
                'coupon_applicable_unique'
            );
            $table->index(['applicable_id', 'applicable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_applicables');
    }
};
