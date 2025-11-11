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
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('is_blog')->default(false)->after('is_homepage');
            
            // Add index for faster blog queries
            $table->index('is_blog');
            
            // Ensure only one blog page can exist (similar to homepage)
            // This will be enforced at application level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['is_blog']);
            $table->dropColumn('is_blog');
        });
    }
};

