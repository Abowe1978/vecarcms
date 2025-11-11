<?php

namespace App\Modules\Core\Interfaces;

interface IntegrationModuleInterface
{
    /**
     * Get the module name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the module description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Check if the module is configured
     *
     * @return bool
     */
    public function isConfigured(): bool;

    /**
     * Check if the module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Enable the module
     *
     * @return bool
     */
    public function enable(): bool;

    /**
     * Disable the module
     *
     * @return bool
     */
    public function disable(): bool;

    /**
     * Get the module configuration
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Update the module configuration
     *
     * @param array $config
     * @return bool
     */
    public function updateConfig(array $config): bool;

    /**
     * Get configuration fields definition
     * 
     * @return array
     */
    public function getConfigFields(): array;
} 