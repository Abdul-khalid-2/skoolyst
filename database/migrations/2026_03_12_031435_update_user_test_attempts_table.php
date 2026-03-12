<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        Schema::table('user_test_attempts', function (Blueprint $table) {
            // Make mock_test_id nullable (for topic/subject level tests)
            $table->foreignId('mock_test_id')->nullable()->change();
            
            // Add new columns for different test types
            $table->foreignId('topic_id')->nullable()->after('mock_test_id')
                  ->constrained('topics')->onDelete('set null');
            $table->foreignId('subject_id')->nullable()->after('topic_id')
                  ->constrained('subjects')->onDelete('set null');
            $table->foreignId('test_type_id')->nullable()->after('subject_id')
                  ->constrained('test_types')->onDelete('set null');
            
            // Test metadata - Note: time_taken_seconds already exists
            $table->string('ip_address', 45)->nullable()->after('time_taken_seconds');
            $table->string('user_agent')->nullable()->after('ip_address');
            
            // Test source/tracking
            $table->enum('test_source', ['topic_test', 'subject_test', 'mock_test', 'practice'])
                  ->default('topic_test')->after('user_agent');
            
            // Additional metrics - Using total_marks_obtained instead of obtained_marks
            // since that's what exists in your table
            $table->decimal('accuracy', 5, 2)->nullable()->after('percentage');
                        
            // Add negative_marks_obtained after total_marks_obtained
            $table->integer('negative_marks_obtained')->default(0)->after('total_marks_obtained');
            
            // Indexes for new columns
            $table->index(['topic_id', 'subject_id']);
            $table->index('test_source');
            $table->index('test_type_id');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('user_test_attempts', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['test_type_id']);
            
            $table->dropColumn([
                'topic_id',
                'subject_id',
                'test_type_id',
                'ip_address',
                'user_agent',
                'test_source',
                'accuracy',
                'negative_marks_obtained'
            ]);
            
            // Make mock_test_id required again
            $table->foreignId('mock_test_id')->nullable(false)->change();
        });
    }
};