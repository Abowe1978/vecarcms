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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mailchimp_subscriber_id')->nullable()->after('stripe_customer_id');
            $table->enum('mailchimp_status', ['subscribed', 'unsubscribed', 'pending', 'cleaned'])->nullable()->after('mailchimp_subscriber_id');
            $table->timestamp('mailchimp_synced_at')->nullable()->after('mailchimp_status');
            
            $table->index('mailchimp_subscriber_id');
            $table->index('mailchimp_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['mailchimp_subscriber_id']);
            $table->dropIndex(['mailchimp_status']);
            $table->dropColumn(['mailchimp_subscriber_id', 'mailchimp_status', 'mailchimp_synced_at']);
        });
    }
};
