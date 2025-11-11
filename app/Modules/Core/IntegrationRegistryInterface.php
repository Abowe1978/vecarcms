<?php

namespace App\Modules\Core;

use App\Modules\Core\Interfaces\IntegrationModuleInterface;

interface IntegrationRegistryInterface
{
    /**
     * Register an integration module
     *
     * @param IntegrationModuleInterface $module
     * @return void
     */
    public function register(IntegrationModuleInterface $module): void;

    /**
     * Get all registered modules
     *
     * @return array
     */
    public function getModules(): array;

    /**
     * Get a module by name
     *
     * @param string $name
     * @return IntegrationModuleInterface|null
     */
    public function getModule(string $name): ?IntegrationModuleInterface;

    /**
     * Check if a module is registered
     *
     * @param string $name
     * @return bool
     */
    public function hasModule(string $name): bool;

    /**
     * Get all enabled modules
     *
     * @return array
     */
    public function getEnabledModules(): array;

    /**
     * Get all configured modules
     *
     * @return array
     */
    public function getConfiguredModules(): array;
} 