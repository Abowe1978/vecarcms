<?php

namespace Modules\GoCardless\app\Module;

use App\Modules\Core\AbstractIntegrationModule;
use Illuminate\Support\Facades\File;

class GoCardlessModule extends AbstractIntegrationModule
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name = 'GoCardless';

    /**
     * The module description
     *
     * @var string
     */
    protected string $description = 'Integra GoCardless per addebiti diretti.';

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        return !empty($config['access_token']) && !empty($config['webhook_endpoint_secret']) && !empty($config['environment']);
    }

    /**
     * Get configuration fields definition from JSON file
     *
     * @return array
     */
    public function getConfigFields(): array
    {
        $fieldsPath = module_path('GoCardless', 'config/fields.json');

        if (!File::exists($fieldsPath)) {
            return [];
        }

        try {
            $fields = json_decode(File::get($fieldsPath), true);

            if (!$fields) {
                return [];
            }

            $configFields = [];
            foreach ($fields as $fieldName => $fieldData) {
                if (is_string($fieldData)) { // Simple field type like "password"
                    $fieldType = $fieldData;
                    $options = [];
                } else { // Complex field like select with options
                    $fieldType = $fieldData['type'];
                    $options = $fieldData['options'] ?? [];
                }

                $configFields[$fieldName] = [
                    'type' => $fieldType,
                    'label' => $this->getFieldLabel($fieldName),
                    'required' => $this->isFieldRequired($fieldName),
                    'help' => $this->getFieldHelp($fieldName),
                    'default' => $this->getFieldDefault($fieldName),
                    'options' => $options,
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
            'access_token' => 'Access Token',
            'webhook_endpoint_secret' => 'Webhook Endpoint Secret',
            'environment' => 'Environment',
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
        return in_array($fieldName, ['access_token', 'webhook_endpoint_secret', 'environment']);
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
            'access_token' => 'Il tuo access token per l\'API di GoCardless.',
            'webhook_endpoint_secret' => 'La chiave segreta per verificare le notifiche webhook.',
            'environment' => 'Seleziona "Sandbox" per i test o "Live" per le transazioni reali.',
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
            'environment' => 'sandbox',
        ];

        return $defaults[$fieldName] ?? null;
    }
} 