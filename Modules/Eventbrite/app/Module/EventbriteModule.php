<?php

namespace Modules\Eventbrite\app\Module;

use App\Modules\Core\AbstractIntegrationModule;
use Illuminate\Support\Facades\File;

class EventbriteModule extends AbstractIntegrationModule
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name = 'Eventbrite';

    /**
     * The module description
     *
     * @var string
     */
    protected string $description = 'Integra i tuoi eventi Eventbrite.';

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        return !empty($config['private_token']);
    }

    /**
     * Get configuration fields definition from JSON file
     *
     * @return array
     */
    public function getConfigFields(): array
    {
        $fieldsPath = module_path('Eventbrite', 'config/fields.json');

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
            'private_token' => 'Private Token',
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
        return in_array($fieldName, ['private_token']);
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
            'private_token' => 'Il tuo token privato di Eventbrite per l\'accesso all\'API.',
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