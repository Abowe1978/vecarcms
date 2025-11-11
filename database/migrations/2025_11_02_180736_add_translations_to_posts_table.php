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
        Schema::table('posts', function (Blueprint $table) {
            $table->json('title_translations')->nullable()->after('title');
            $table->json('content_translations')->nullable()->after('content');
            $table->json('excerpt_translations')->nullable()->after('excerpt');
            $table->string('locale')->default('en')->after('slug');
            
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title_translations', 'content_translations', 'excerpt_translations', 'locale']);
        });
    }
};
