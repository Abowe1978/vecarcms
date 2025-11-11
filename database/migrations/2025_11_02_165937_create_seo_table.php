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
        Schema::create('seo', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation
            $table->morphs('seoable'); // seoable_id, seoable_type
            
            // Basic Meta Tags
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            
            // Open Graph (Facebook)
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            
            // Twitter Card
            $table->string('twitter_card')->default('summary');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            
            // Indexing
            $table->boolean('index')->default(true); // noindex
            $table->boolean('follow')->default(true); // nofollow
            
            // Schema.org
            $table->json('schema_markup')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['seoable_id', 'seoable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
