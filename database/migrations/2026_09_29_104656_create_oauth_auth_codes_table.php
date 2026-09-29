<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('client_id'); // matches oauth_clients.client_id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('redirect_uri'); // must match exactly on exchange
            $table->string('state')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_auth_codes');
    }
};
