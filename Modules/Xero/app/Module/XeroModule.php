<?php

namespace Modules\Xero\app\Module;

use App\Modules\Core\AbstractIntegrationModule;
use Illuminate\Support\Facades\File;

class XeroModule extends AbstractIntegrationModule
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name = 'Xero';

    /**
     * The module description
     *
     * @var string
     */
    protected string $description = 'Sincronizza fatture, contatti e altro con Xero.';

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        // For OAuth, we consider it "configured" if we have the client credentials.
        // The actual connection test will happen during the OAuth flow.
        return !empty($config['client_id']) && !empty($config['client_secret']);
    }

    /**
     * Get configuration fields definition from JSON file
     *
     * @return array
     */
    public function getConfigFields(): array
    {
        $fieldsPath = module_path('Xero', 'config/fields.json');

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
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'redirect_uri' => 'Redirect URI',
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
        return in_array($fieldName, ['client_id', 'client_secret', 'redirect_uri']);
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
            'client_id' => 'Il Client ID della tua app su Xero Developer Portal.',
            'client_secret' => 'Il Client Secret della tua app su Xero Developer Portal.',
            'redirect_uri' => 'Copia questo URI e incollalo nelle impostazioni della tua app Xero nella sezione "OAuth 2.0 redirect URI".',
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
            'redirect_uri' => url('/admin/integrations/xero/oauth/callback'),
        ];

        return $defaults[$fieldName] ?? null;
    }
} 