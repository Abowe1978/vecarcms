<?php

namespace Modules\Mailchimp\app\Module;

use App\Modules\Core\AbstractIntegrationModule;
use Illuminate\Support\Facades\File;

class MailchimpModule extends AbstractIntegrationModule
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name = 'Mailchimp';

    /**
     * The module description
     *
     * @var string
     */
    protected string $description = 'Integrazione con Mailchimp per email marketing e newsletter.';

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        return !empty($config['api_key']) && !empty($config['list_id']);
    }

    /**
     * Get configuration fields definition from JSON file
     *
     * @return array
     */
    public function getConfigFields(): array
    {
        $fieldsPath = module_path('Mailchimp', 'config/fields.json');

        if (!File::exists($fieldsPath)) {
            return [];
        }

        try {
            $fields = json_decode(File::get($fieldsPath), true);

            if (!$fields) {
                return [];
            }

            // Convert the simple format to the expected format for the view
            $configFields = [];

            foreach ($fields as $fieldName => $fieldType) {
                $configFields[$fieldName] = [
                    'type' => $fieldType,
                    'label' => $this->getFieldLabel($fieldName),
                    'required' => $this->isFieldRequired($fieldName),
                    'help' => $this->getFieldHelp($fieldName),
                    'default' => $this->getFieldDefault($fieldName),
                ];

                if ($fieldType === 'checkbox') {
                    $configFields[$fieldName]['checkbox_label'] = $this->getFieldLabel($fieldName);
                }
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
            'list_id' => 'Audience ID',
            'add_members_auto' => 'Aggiunta Automatica',
            'double_optin' => 'Double Opt-in',
            'webhook_url' => 'Webhook URL',
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
        return in_array($fieldName, ['api_key', 'list_id']);
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
            'api_key' => 'La chiave API di Mailchimp per l\'autenticazione.',
            'list_id' => 'L\'ID della lista/audience a cui aggiungere i membri.',
            'add_members_auto' => 'Se abilitato, i nuovi membri verranno automaticamente aggiunti alla lista Mailchimp.',
            'double_optin' => 'Se abilitato, i nuovi iscritti dovranno confermare la loro iscrizione via email.',
            'webhook_url' => 'URL per ricevere le notifiche da Mailchimp (lasciare vuoto per usare quello di default).',
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
            'add_members_auto' => true,
            'double_optin' => false,
            'webhook_url' => route('mailchimp.webhook'),
        ];

        return $defaults[$fieldName] ?? null;
    }
} 