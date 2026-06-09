<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE schools MODIFY admission_fees VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE schools MODIFY admission_fees DECIMAL(10, 2) NULL');
    }
};
