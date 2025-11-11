<?php

namespace App\Modules\Core;

use App\Modules\Core\Interfaces\IntegrationModuleInterface;

class IntegrationRegistry implements IntegrationRegistryInterface
{
    /**
     * The registered modules
     *
     * @var array
     */
    protected array $modules = [];

    /**
     * Register an integration module
     *
     * @param IntegrationModuleInterface $module
     * @return void
     */
    public function register(IntegrationModuleInterface $module): void
    {
        $this->modules[$module->getName()] = $module;
    }

    /**
     * Get all registered modules
     *
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * Get a module by name
     *
     * @param string $name
     * @return IntegrationModuleInterface|null
     */
    public function getModule(string $name): ?IntegrationModuleInterface
    {
        return $this->modules[$name] ?? null;
    }

    /**
     * Check if a module is registered
     *
     * @param string $name
     * @return bool
     */
    public function hasModule(string $name): bool
    {
        return isset($this->modules[$name]);
    }

    /**
     * Get all enabled modules
     *
     * @return array
     */
    public function getEnabledModules(): array
    {
        return array_filter($this->modules, function (IntegrationModuleInterface $module) {
            return $module->isEnabled();
        });
    }

    /**
     * Get all configured modules
     *
     * @return array
     */
    public function getConfiguredModules(): array
    {
        return array_filter($this->modules, function (IntegrationModuleInterface $module) {
            return $module->isConfigured();
        });
    }
} 