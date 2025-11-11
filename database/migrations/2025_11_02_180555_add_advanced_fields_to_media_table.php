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
        Schema::table('media', function (Blueprint $table) {
            $table->string('alt_text')->nullable()->after('mime_type');
            $table->string('caption')->nullable()->after('alt_text');
            $table->text('description')->nullable()->after('caption');
            $table->string('folder')->default('/')->after('description'); // Folder path
            $table->foreignId('uploaded_by')->nullable()->after('folder')->constrained('users')->onDelete('set null');
            $table->integer('width')->nullable()->after('size');
            $table->integer('height')->nullable()->after('width');
            $table->boolean('is_optimized')->default(false)->after('height');
            
            $table->index('folder');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn([
                'alt_text',
                'caption',
                'description',
                'folder',
                'uploaded_by',
                'width',
                'height',
                'is_optimized',
            ]);
        });
    }
};
