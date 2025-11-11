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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation
            $table->morphs('revisionable'); // revisionable_id, revisionable_type
            
            // Revision metadata
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type')->default('manual'); // manual, auto_save, scheduled
            
            // Content snapshot (JSON)
            $table->json('content'); // Full content snapshot
            
            // Revision info
            $table->string('title')->nullable(); // Revision title/note
            $table->text('summary')->nullable(); // Summary of changes
            
            $table->timestamps();
            
            // Indexes
            $table->index(['revisionable_id', 'revisionable_type']);
            $table->index('user_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
