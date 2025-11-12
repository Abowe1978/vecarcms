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
        Schema::table('widget_zones', function (Blueprint $table) {
            $table->dropUnique('widget_zones_name_unique');
            $table->unique(['name', 'theme'], 'widget_zones_name_theme_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('widget_zones', function (Blueprint $table) {
            $table->dropUnique('widget_zones_name_theme_unique');
            $table->unique('name', 'widget_zones_name_unique');
        });
    }
};


