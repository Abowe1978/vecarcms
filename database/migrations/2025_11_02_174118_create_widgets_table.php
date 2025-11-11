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
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('widget_zones')->onDelete('cascade');
            
            // Widget type and configuration
            $table->string('type'); // recent_posts, categories, tag_cloud, custom_html, etc.
            $table->string('title')->nullable();
            $table->json('settings')->nullable(); // Widget-specific settings
            
            // Display & Order
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Visibility Rules (WordPress-like)
            $table->json('visibility_rules')->nullable(); // Show on specific pages, logged in/out, roles
            
            // Scheduling
            $table->timestamp('show_from')->nullable();
            $table->timestamp('show_until')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('zone_id');
            $table->index('type');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widgets');
    }
};
