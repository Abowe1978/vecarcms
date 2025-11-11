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
        Schema::table('integrations', function (Blueprint $table) {
            // Check if columns exist before dropping them
            if (Schema::hasColumn('integrations', 'webhook_url')) {
                $table->dropColumn('webhook_url');
            }
            if (Schema::hasColumn('integrations', 'last_error')) {
                $table->dropColumn('last_error');
            }
            if (Schema::hasColumn('integrations', 'last_success')) {
                $table->dropColumn('last_success');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->string('webhook_url')->nullable();
            $table->timestamp('last_error')->nullable();
            $table->timestamp('last_success')->nullable();
        });
    }
}; 