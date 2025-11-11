<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('name');
            $table->string('title')->nullable()->after('surname');
            $table->string('website')->nullable()->after('title');
            $table->enum('communication_preferences', ['email', 'sms', 'both', 'none'])->default('email')->after('website');
            $table->text('address')->nullable()->after('communication_preferences');
            $table->string('postcode')->nullable()->after('address');
            $table->string('city')->nullable()->after('postcode');
            $table->string('country')->nullable()->after('city');
            $table->string('mobile_phone')->nullable()->after('country');
            $table->boolean('gdpr_consent')->default(false)->after('mobile_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'title',
                'website',
                'communication_preferences',
                'address',
                'postcode',
                'city',
                'country',
                'mobile_phone',
                'gdpr_consent',
            ]);
        });
    }
}
