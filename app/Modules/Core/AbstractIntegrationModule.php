<?php

namespace App\Modules\Core;

use App\Modules\Core\Interfaces\IntegrationModuleInterface;
use Illuminate\Support\Facades\Config;
use App\Models\Integration;

abstract class AbstractIntegrationModule implements IntegrationModuleInterface
{
    /**
     * The module name
     *
     * @var string
     */
    protected string $name;

    /**
     * The module description
     *
     * @var string
     */
    protected string $description;

    /**
     * The configuration key
     *
     * @var string
     */
    protected string $configKey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configKey = 'modules.' . strtolower($this->getName());
    }

    /**
     * Get the module name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the module description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Check if the module is configured
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        $config = $this->getConfig();
        return !empty($config) && $this->validateConfig($config);
    }

    /**
     * Validate the configuration
     *
     * @param array $config
     * @return bool
     */
    abstract protected function validateConfig(array $config): bool;

    /**
     * Check if the module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        $integration = Integration::where('module_name', $this->getName())->first();
        return $integration ? $integration->is_enabled : false;
    }

    /**
     * Enable the module
     *
     * @return bool
     */
    public function enable(): bool
    {
        // The IntegrationService handles the database update.
        // This method simply needs to return a success state.
        return true;
    }

    /**
     * Disable the module
     *
     * @return bool
     */
    public function disable(): bool
    {
        // The IntegrationService handles the database update.
        return true;
    }

    /**
     * Get the module configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return Config::get($this->configKey, []);
    }

    /**
     * Update the module configuration
     *
     * @param array $config
     * @return bool
     */
    public function updateConfig(array $config): bool
    {
        Config::set($this->configKey, $config);
        return true;
    }

    /**
     * Get configuration fields definition
     * 
     * @return array
     */
    public function getConfigFields(): array
    {
        return [];
    }
} 