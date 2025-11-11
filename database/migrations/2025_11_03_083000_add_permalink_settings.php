<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add permalink structure settings (WordPress-like)
        $permalinkSettings = [
            [
                'key' => 'permalink_post_structure',
                'value' => '/%postname%/',
                'type' => 'string',
                'group' => 'permalinks',
                'autoload' => true,
                'description' => 'Post permalink structure. Available tags: %postname%, %post_id%, %year%, %month%, %day%',
            ],
            [
                'key' => 'permalink_page_structure',
                'value' => '/%pagename%/',
                'type' => 'string',
                'group' => 'permalinks',
                'autoload' => true,
                'description' => 'Page permalink structure. Available tags: %pagename%',
            ],
            [
                'key' => 'permalink_category_structure',
                'value' => '/%category%/',
                'type' => 'string',
                'group' => 'permalinks',
                'autoload' => true,
                'description' => 'Category permalink structure. Clean URLs without prefix (WordPress-like)',
            ],
            [
                'key' => 'permalink_tag_structure',
                'value' => '/tag/%tag%/',
                'type' => 'string',
                'group' => 'permalinks',
                'autoload' => true,
                'description' => 'Tag permalink structure',
            ],
        ];

        foreach ($permalinkSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn('key', [
            'permalink_post_structure',
            'permalink_page_structure',
            'permalink_category_structure',
            'permalink_tag_structure',
        ])->delete();
    }
};

