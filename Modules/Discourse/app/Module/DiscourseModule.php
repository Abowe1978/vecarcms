<?php

namespace Modules\Discourse\app\Module;

use App\Modules\Core\AbstractIntegrationModule;
use Illuminate\Support\Facades\File;

class DiscourseModule extends AbstractIntegrationModule
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name = 'Discourse';

    /**
     * The module description
     *
     * @var string
     */
    protected string $description = 'Integra il tuo forum Discourse per SSO e altre funzionalità.';

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        return !empty($config['discourse_url']) && !empty($config['api_key']) && !empty($config['discourse_connect_secret']);
    }

    /**
     * Get configuration fields definition from JSON file
     *
     * @return array
     */
    public function getConfigFields(): array
    {
        $fieldsPath = module_path('Discourse', 'config/fields.json');

        if (!File::exists($fieldsPath)) {
            return [];
        }

        try {
            $fields = json_decode(File::get($fieldsPath), true);

            if (!$fields) {
                return [];
            }

            $configFields = [];
            foreach ($fields as $fieldName => $fieldType) {
                $configFields[$fieldName] = [
                    'type' => $fieldType,
                    'label' => $this->getFieldLabel($fieldName),
                    'required' => $this->isFieldRequired($fieldName),
                    'help' => $this->getFieldHelp($fieldName),
                    'default' => $this->getFieldDefault($fieldName),
                ];
            }

            return $configFields;

        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get field label for translation
     *
     * @param string $fieldName
     * @return string
     */
    protected function getFieldLabel(string $fieldName): string
    {
        $labels = [
            'discourse_url' => 'Discourse URL',
            'api_key' => 'API Key',
            'api_username' => 'API Username',
            'discourse_connect_secret' => 'DiscourseConnect Secret',
        ];

        return $labels[$fieldName] ?? ucfirst(str_replace('_', ' ', $fieldName));
    }

    /**
     * Check if field is required
     *
     * @param string $fieldName
     * @return bool
     */
    protected function isFieldRequired(string $fieldName): bool
    {
        return in_array($fieldName, ['discourse_url', 'api_key', 'discourse_connect_secret']);
    }

    /**
     * Get field help text
     *
     * @param string $fieldName
     * @return string
     */
    protected function getFieldHelp(string $fieldName): string
    {
        $helpTexts = [
            'discourse_url' => 'L\'URL completo del tuo forum Discourse (es. https://forum.example.com).',
            'api_key' => 'La chiave API "All Users" generata in Discourse per le operazioni server-to-server.',
            'api_username' => 'Lo username associato alla chiave API (solitamente "system").',
            'discourse_connect_secret' => 'La chiave segreta per abilitare il Single Sign-On (DiscourseConnect).',
        ];

        return $helpTexts[$fieldName] ?? '';
    }

    /**
     * Get field default value
     *
     * @param string $fieldName
     * @return mixed
     */
    protected function getFieldDefault(string $fieldName)
    {
        $defaults = [
            'api_username' => 'system',
        ];

        return $defaults[$fieldName] ?? null;
    }
} 