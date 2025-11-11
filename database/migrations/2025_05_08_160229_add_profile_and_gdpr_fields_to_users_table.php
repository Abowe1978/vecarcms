<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->after('mobile_phone')->nullable();
            $table->string('formatted_name')->after('title')->nullable();
            $table->string('salutation')->after('formatted_name')->nullable();
            $table->string('known_as')->after('salutation')->nullable();
            $table->string('iso_country')->after('country')->nullable();
            $table->boolean('can_process')->default(false);
            $table->boolean('can_email')->default(false);
            $table->boolean('can_post')->default(false);
            $table->boolean('can_sms')->default(false);
            $table->boolean('can_telephone')->default(false);
            $table->boolean('gdpr_can_social_media')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'formatted_name',
                'salutation',
                'known_as',
                'iso_country',
                'can_process',
                'can_email',
                'can_post',
                'can_sms',
                'can_telephone',
                'gdpr_can_social_media',
            ]);
        });
    }
}; 