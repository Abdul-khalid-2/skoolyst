<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // database/migrations/2025_10_27_000000_create_contact_inquiries_table.php
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // Contact Information
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('subject', [
                'admission',
                'tour',
                'general',
                'feedback',
                'other'
            ])->default('general');
            $table->string('custom_subject')->nullable(); // For "other" subject

            // Message
            $table->text('message');

            // Status Tracking
            $table->enum('status', [
                'new',
                'in_progress',
                'resolved',
                'closed'
            ])->default('new');

            // Response Tracking
            $table->text('admin_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('responded_at')->nullable();

            // Metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('school_id');
            $table->index('branch_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('subject');
            $table->index('created_at');
            $table->index(['school_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
