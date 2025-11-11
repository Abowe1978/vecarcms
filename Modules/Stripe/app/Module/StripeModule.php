<?php

namespace Modules\Stripe\app\Module;

use App\Modules\Core\AbstractIntegrationModule;
use Modules\Stripe\app\Services\Interfaces\StripeServiceInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class StripeModule extends AbstractIntegrationModule
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name = 'Stripe';

    /**
     * The module description
     *
     * @var string
     */
    protected string $description = 'Integrate Stripe payment processing';

    /**
     * Get the Stripe service
     */
    protected function getStripeService(): StripeServiceInterface
    {
        return App::make(StripeServiceInterface::class);
    }

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        return !empty($config['public_key']) && 
               !empty($config['secret_key']) && 
               !empty($config['webhook_secret']);
    }

    /**
     * Get the module configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->getStripeService()->getConfig();
    }

    /**
     * Update the module configuration
     *
     * @param array $config
     * @return bool
     */
    public function updateConfig(array $config): bool
    {
        return $this->getStripeService()->updateConfig($config);
    }

    /**
     * Get configuration fields definition from JSON file
     * 
     * @return array
     */
    public function getConfigFields(): array
    {
        $fieldsPath = module_path('Stripe', 'config/fields.json');
        
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
                ];
                
                // Add specific options for select fields
                if ($fieldType === 'select') {
                    $configFields[$fieldName]['options'] = $this->getSelectOptions($fieldName);
                }
                
                // Add checkbox label for checkbox fields
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
            'public_key' => 'Public Key',
            'secret_key' => 'Secret Key',
            'webhook_secret' => 'Webhook Secret',
            'webhook_url' => 'Webhook URL',
            'currency' => 'Currency',
            'sandbox_mode' => 'Sandbox Mode',
            'statement_descriptor' => 'Statement Descriptor',
            'automatic_tax' => 'Automatic Tax',
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
        $requiredFields = ['public_key', 'secret_key', 'webhook_secret'];
        return in_array($fieldName, $requiredFields);
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
            'public_key' => 'Your Stripe publishable key (starts with pk_)',
            'secret_key' => 'Your Stripe secret key (starts with sk_)',
            'webhook_secret' => 'Your Stripe webhook endpoint secret',
            'webhook_url' => 'Your Stripe webhook endpoint URL (optional, defaults to system webhook)',
            'currency' => 'The currency for payments (e.g., gbp, usd, eur)',
            'sandbox_mode' => 'Enable sandbox mode for testing',
            'statement_descriptor' => 'Description that appears on customer statements',
            'automatic_tax' => 'Enable automatic tax calculation',
        ];
        
        return $helpTexts[$fieldName] ?? '';
    }

    /**
     * Get select options for select fields
     * 
     * @param string $fieldName
     * @return array
     */
    protected function getSelectOptions(string $fieldName): array
    {
        if ($fieldName === 'currency') {
            return [
                'gbp' => 'British Pound (GBP)',
                'usd' => 'US Dollar (USD)',
                'eur' => 'Euro (EUR)',
                'cad' => 'Canadian Dollar (CAD)',
                'aud' => 'Australian Dollar (AUD)',
            ];
        }
        
        return [];
    }
} 