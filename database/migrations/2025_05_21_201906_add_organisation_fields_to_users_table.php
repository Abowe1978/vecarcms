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
            $table->string('organisation')->nullable();
            $table->string('registered_name')->nullable();
            $table->string('trading_name')->nullable();
            $table->string('organisation_type')->nullable();
            $table->string('vat_number')->nullable();
            $table->boolean('vat_registered')->nullable();
            $table->string('charity_number')->nullable();
            $table->string('company_number')->nullable();
            $table->string('industry')->nullable();
            $table->string('sector')->nullable();
            $table->string('turnover_band')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
