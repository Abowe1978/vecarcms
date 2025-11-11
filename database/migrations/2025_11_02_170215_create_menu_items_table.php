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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            
            // Item content
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('target')->default('_self'); // _self, _blank
            
            // Item type
            $table->enum('type', ['custom', 'page', 'post', 'category', 'tag'])->default('custom');
            $table->string('object_id')->nullable(); // ID of linked object (page_id, post_id, etc.)
            
            // Display & Order
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            
            // CSS Classes & Attributes
            $table->string('css_class')->nullable();
            $table->string('icon')->nullable(); // Icon class (e.g., 'fa-home')
            
            // Visibility Rules
            $table->json('visibility_rules')->nullable(); // Role-based, logged in/out, etc.
            
            $table->timestamps();
            
            // Indexes
            $table->index('menu_id');
            $table->index('parent_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
