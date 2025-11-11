<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // ============================================
            // GENERAL SETTINGS
            // ============================================
            [
                'key' => 'site_name',
                'value' => 'VeCarCMS',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nome del sito',
                'autoload' => true,
            ],
            [
                'key' => 'site_tagline',
                'value' => 'A powerful WordPress-like CMS built with Laravel',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Tagline/Slogan del sito',
                'autoload' => true,
            ],
            [
                'key' => 'site_email',
                'value' => 'admin@example.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Email principale del sito',
                'autoload' => true,
            ],
            [
                'key' => 'timezone',
                'value' => 'Europe/Rome',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Timezone del sito',
                'autoload' => true,
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Formato data',
                'autoload' => true,
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Formato ora',
                'autoload' => true,
            ],

            // ============================================
            // REGISTRATION SETTINGS
            // ============================================
            [
                'key' => 'allow_user_registration',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'registration',
                'description' => 'Permetti registrazione utenti',
                'autoload' => true,
            ],
            [
                'key' => 'default_user_role',
                'value' => 'user',
                'type' => 'string',
                'group' => 'registration',
                'description' => 'Ruolo predefinito per nuovi utenti',
                'autoload' => true,
            ],
            [
                'key' => 'require_email_verification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'registration',
                'description' => 'Richiedi verifica email',
                'autoload' => true,
            ],
            [
                'key' => 'registration_notification_email',
                'value' => 'admin@example.com',
                'type' => 'string',
                'group' => 'registration',
                'description' => 'Email per notifiche nuove registrazioni',
                'autoload' => false,
            ],

            // ============================================
            // COMMENTS SETTINGS
            // ============================================
            [
                'key' => 'comments_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'comments',
                'description' => 'Abilita sistema commenti',
                'autoload' => true,
            ],
            [
                'key' => 'comments_auto_approve',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'comments',
                'description' => 'Approva automaticamente i commenti',
                'autoload' => true,
            ],
            [
                'key' => 'comments_per_page',
                'value' => '20',
                'type' => 'integer',
                'group' => 'comments',
                'description' => 'Numero commenti per pagina',
                'autoload' => true,
            ],
            [
                'key' => 'comments_moderation_email',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'comments',
                'description' => 'Invia email per moderazione commenti',
                'autoload' => false,
            ],
            [
                'key' => 'comments_allow_guests',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'comments',
                'description' => 'Permetti commenti da ospiti',
                'autoload' => true,
            ],

            // ============================================
            // SEO SETTINGS
            // ============================================
            [
                'key' => 'seo_default_meta_title',
                'value' => 'VeCarCMS',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Meta title predefinito',
                'autoload' => true,
            ],
            [
                'key' => 'seo_default_meta_description',
                'value' => 'A powerful WordPress-like CMS built with Laravel',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Meta description predefinita',
                'autoload' => true,
            ],
            [
                'key' => 'seo_default_meta_keywords',
                'value' => 'cms,laravel,wordpress,vecarcms',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Meta keywords predefinite',
                'autoload' => true,
            ],
            [
                'key' => 'sitemap_cache_duration',
                'value' => '3600',
                'type' => 'integer',
                'group' => 'seo',
                'description' => 'Durata cache sitemap (secondi)',
                'autoload' => false,
            ],

            // ============================================
            // THEME SETTINGS
            // ============================================
            [
                'key' => 'active_theme',
                'value' => 'default',
                'type' => 'string',
                'group' => 'theme',
                'description' => 'Tema attivo',
                'autoload' => true,
            ],
            [
                'key' => 'theme_cache_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'theme',
                'description' => 'Abilita cache temi',
                'autoload' => true,
            ],

            // ============================================
            // PAGE BUILDER SETTINGS
            // ============================================
            [
                'key' => 'page_builder_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'page_builder',
                'description' => 'Abilita Page Builder',
                'autoload' => true,
            ],
            [
                'key' => 'page_builder_auto_save_interval',
                'value' => '30',
                'type' => 'integer',
                'group' => 'page_builder',
                'description' => 'Intervallo auto-salvataggio (secondi)',
                'autoload' => false,
            ],
            [
                'key' => 'page_builder_max_revisions',
                'value' => '10',
                'type' => 'integer',
                'group' => 'page_builder',
                'description' => 'Massimo numero revisioni',
                'autoload' => false,
            ],

            // ============================================
            // MEDIA SETTINGS
            // ============================================
            [
                'key' => 'media_max_file_size',
                'value' => '10240',
                'type' => 'integer',
                'group' => 'media',
                'description' => 'Dimensione massima file (KB)',
                'autoload' => true,
            ],
            [
                'key' => 'media_optimization_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'media',
                'description' => 'Abilita ottimizzazione immagini',
                'autoload' => true,
            ],
            [
                'key' => 'media_optimization_quality',
                'value' => '80',
                'type' => 'integer',
                'group' => 'media',
                'description' => 'Qualità ottimizzazione (1-100)',
                'autoload' => false,
            ],

            // ============================================
            // MULTILINGUA
            // ============================================
            [
                'key' => 'supported_locales',
                'value' => json_encode(['it', 'en']),
                'type' => 'json',
                'group' => 'localization',
                'description' => 'Lingue supportate',
                'autoload' => true,
            ],
            [
                'key' => 'default_locale',
                'value' => 'it',
                'type' => 'string',
                'group' => 'localization',
                'description' => 'Lingua predefinita',
                'autoload' => true,
            ],

            // ============================================
            // ANALYTICS
            // ============================================
            [
                'key' => 'analytics_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'analytics',
                'description' => 'Abilita tracking analytics',
                'autoload' => true,
            ],
            [
                'key' => 'google_analytics_id',
                'value' => '',
                'type' => 'string',
                'group' => 'analytics',
                'description' => 'Google Analytics ID',
                'autoload' => true,
            ],

            // ============================================
            // MAINTENANCE
            // ============================================
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'maintenance',
                'description' => 'Modalità manutenzione',
                'autoload' => true,
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Sito in manutenzione. Torna presto!',
                'type' => 'string',
                'group' => 'maintenance',
                'description' => 'Messaggio manutenzione',
                'autoload' => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Settings seeded successfully!');
        $this->command->info('   - General: 6 settings');
        $this->command->info('   - Registration: 4 settings (auto-registration enabled)');
        $this->command->info('   - Comments: 5 settings');
        $this->command->info('   - SEO: 4 settings');
        $this->command->info('   - Theme: 2 settings');
        $this->command->info('   - Page Builder: 3 settings');
        $this->command->info('   - Media: 3 settings');
        $this->command->info('   - Localization: 2 settings');
        $this->command->info('   - Analytics: 2 settings');
        $this->command->info('   - Maintenance: 2 settings');
    }
}

