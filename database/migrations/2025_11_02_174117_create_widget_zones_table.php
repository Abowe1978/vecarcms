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
        Schema::create('widget_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // sidebar, footer, header, etc.
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('theme')->nullable(); // Which theme this zone belongs to
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['name', 'theme']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_zones');
    }
};
