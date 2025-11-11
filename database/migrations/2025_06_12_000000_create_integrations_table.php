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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module_name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_configured')->default(false);
            $table->json('config')->nullable();
            $table->timestamp('last_error')->nullable();
            $table->timestamp('last_success')->nullable();
            $table->string('webhook_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
}; 