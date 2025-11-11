<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation (commentable)
            $table->morphs('commentable'); // commentable_id, commentable_type
            
            // Nested comments support
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            
            // Author (can be user or guest)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('author_name')->nullable(); // For guest comments
            $table->string('author_email')->nullable(); // For guest comments
            $table->string('author_url')->nullable(); // For guest comments
            $table->string('author_ip')->nullable();
            $table->string('author_user_agent')->nullable();
            
            // Content
            $table->text('content');
            
            // Status & Moderation
            $table->enum('status', ['pending', 'approved', 'spam', 'trash'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Metadata
            $table->integer('likes')->default(0);
            $table->boolean('is_featured')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['commentable_id', 'commentable_type']);
            $table->index('status');
            $table->index('parent_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
