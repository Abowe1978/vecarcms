<?php

namespace Modules\Loqate\app\Module;

use App\Modules\Core\AbstractIntegrationModule;
use Illuminate\Support\Facades\File;

class LoqateModule extends AbstractIntegrationModule
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name = 'Loqate';

    /**
     * The module description
     *
     * @var string
     */
    protected string $description = 'Verifica e arricchisci gli indirizzi con Loqate.';

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        return !empty($config['api_key']);
    }

    /**
     * Get configuration fields definition from JSON file
     *
     * @return array
     */
    public function getConfigFields(): array
    {
        $fieldsPath = module_path('Loqate', 'config/fields.json');

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
            'api_key' => 'API Key',
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
        return in_array($fieldName, ['api_key']);
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
            'api_key' => 'La tua API Key per accedere ai servizi Loqate.',
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
        return null;
    }
} 