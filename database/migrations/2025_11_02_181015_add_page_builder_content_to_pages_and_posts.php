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
            $table->json('page_builder_content')->nullable()->after('content');
            $table->boolean('use_page_builder')->default(false)->after('page_builder_content');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->json('page_builder_content')->nullable()->after('content');
            $table->boolean('use_page_builder')->default(false)->after('page_builder_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['page_builder_content', 'use_page_builder']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['page_builder_content', 'use_page_builder']);
        });
    }
};
