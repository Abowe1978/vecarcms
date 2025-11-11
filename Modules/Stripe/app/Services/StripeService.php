<?php

namespace Modules\Stripe\app\Services;

use App\Models\Integration;
use Modules\Stripe\app\Services\Interfaces\StripeServiceInterface;
use Illuminate\Support\Facades\Config;

class StripeService implements StripeServiceInterface
{
    /**
     * Get the Stripe configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        $integration = Integration::where('module_name', 'Stripe')->first();
        return $integration ? $integration->config : [];
    }

    /**
     * Update the Stripe configuration
     *
     * @param array $config
     * @return bool
     */
    public function updateConfig(array $config): bool
    {
        try {
            $integration = Integration::where('module_name', 'Stripe')->first();
            if (!$integration) {
                return false;
            }
            
            $integration->config = $config;
            $integration->is_configured = $this->validateConfig($config);
            return $integration->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    protected function validateConfig(array $config): bool
    {
        $publicKey = $config['public_key'] ?? $config['key'] ?? null;
        $secretKey = $config['secret_key'] ?? $config['secret'] ?? null;
        
        return !empty($publicKey) && 
               !empty($secretKey) && 
               !empty($config['webhook_secret']);
    }

    /**
     * Get Stripe public key
     *
     * @return string
     */
    public function getPublicKey(): string
    {
        $config = $this->getConfig();
        return $config['public_key'] ?? $config['key'] ?? '';
    }

    /**
     * Get Stripe secret key
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        $config = $this->getConfig();
        return $config['secret_key'] ?? $config['secret'] ?? '';
    }

    /**
     * Get webhook secret
     *
     * @return string
     */
    public function getWebhookSecret(): string
    {
        $config = $this->getConfig();
        return $config['webhook_secret'] ?? '';
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        $config = $this->getConfig();
        return $config['currency'] ?? 'gbp';
    }

    /**
     * Check if sandbox mode is enabled
     *
     * @return bool
     */
    public function isSandboxMode(): bool
    {
        $config = $this->getConfig();
        return $config['sandbox'] ?? true;
    }

    /**
     * Get supported payment methods
     *
     * @return array
     */
    public function getPaymentMethods(): array
    {
        return config('stripe.payment_methods', ['card']);
    }

    /**
     * Get statement descriptor
     *
     * @return string
     */
    public function getStatementDescriptor(): string
    {
        $config = $this->getConfig();
        return $config['statement_descriptor'] ?? 'RREC Membership';
    }

    /**
     * Check if automatic tax is enabled
     *
     * @return bool
     */
    public function isAutomaticTaxEnabled(): bool
    {
        $config = $this->getConfig();
        return $config['automatic_tax'] ?? false;
    }

    /**
     * Update environment file
     *
     * @param array $data
     * @return void
     */
    protected function updateEnvironmentFile(array $data)
    {
        if (empty($data)) {
            return;
        }

        $path = base_path('.env');

        if (!file_exists($path)) {
            throw new \Exception('.env file not found');
        }

        $content = file_get_contents($path);

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            // Convert boolean to string
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            // Escape quotes
            $value = str_replace('"', '\\"', $value);

            // Update existing value
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $content
                );
            }
            // Add new value
            else {
                $content .= PHP_EOL . "{$key}=\"{$value}\"";
            }
        }

        file_put_contents($path, $content);
    }

    public function isConfigured(): bool
    {
        $config = $this->getConfig();
        return $this->validateConfig($config);
    }
} 