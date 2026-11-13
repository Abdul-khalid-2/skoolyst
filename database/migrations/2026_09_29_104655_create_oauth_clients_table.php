<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Skoolyst Blogs"
            $table->string('client_id')->unique();
            $table->string('client_secret'); // hashed
            $table->json('redirect_uris'); // exact-match allow-list
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_clients');
    }
};
