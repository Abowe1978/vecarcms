<?php

namespace App\Services;

use App\Models\Integration;
use App\Modules\Core\ModuleManager;
use App\Repositories\Interfaces\IntegrationRepositoryInterface;
use App\Services\Interfaces\IntegrationServiceInterface;
use Illuminate\Support\Collection;

class IntegrationService implements IntegrationServiceInterface
{
    protected ModuleManager $moduleManager;
    protected IntegrationRepositoryInterface $integrationRepository;

    public function __construct(
        ModuleManager $moduleManager,
        IntegrationRepositoryInterface $integrationRepository
    ) {
        $this->moduleManager = $moduleManager;
        $this->integrationRepository = $integrationRepository;
    }

    public function getAllIntegrations(): Collection
    {
        $modules = $this->moduleManager->all();
        
        return $modules->map(function ($module, $name) {
            $integration = $this->integrationRepository->findByModuleName($name);
            
            if (!$integration->exists) {
                $integration = $this->syncIntegrationWithModule($integration);
            }
            
            return [
                'name' => $module->getName(),
                'description' => $module->getDescription(),
                'is_enabled' => $module->isEnabled(),
                'is_configured' => $module->isConfigured(),
                'module' => $module,
                'model' => $integration,
            ];
        })->values();
    }
    
    public function getIntegration(string $name): ?array
    {
        $module = $this->moduleManager->get($name);
        
        if (!$module) {
            return null;
        }
        
        $integration = $this->integrationRepository->findByModuleName($name);
        
        return [
            'module' => $module,
            'integration' => $integration,
            'config_fields' => method_exists($module, 'getConfigFields') ? $module->getConfigFields() : [],
            'config' => $module->getConfig(),
        ];
    }
    
    public function enableIntegration(string $name): bool
    {
        $module = $this->moduleManager->get($name);
        
        if (!$module) {
            return false;
        }
        
        $integration = $this->integrationRepository->findByModuleName($name);
        $success = $module->enable();
        
        if ($success) {
            $this->integrationRepository->updateStatus($integration, true);
        }
        
        return $success;
    }
    
    public function disableIntegration(string $name): bool
    {
        $module = $this->moduleManager->get($name);
        
        if (!$module) {
            return false;
        }
        
        $integration = $this->integrationRepository->findByModuleName($name);
        $success = $module->disable();
        
        if ($success) {
            $this->integrationRepository->updateStatus($integration, false);
        }
        
        return $success;
    }
    
    public function updateIntegrationConfig(string $name, array $config): bool
    {
        $module = $this->moduleManager->get($name);
        
        if (!$module) {
            return false;
        }
        
        $success = $module->updateConfig($config);
        
        if ($success) {
            $integration = $this->integrationRepository->findByModuleName($name);
            $this->integrationRepository->updateConfig($integration, $config);
            $integration->is_configured = $module->isConfigured();
            $integration->save();
        }
        
        return $success;
    }
    
    public function syncIntegrationWithModule(Integration $integration): Integration
    {
        $module = $this->moduleManager->get($integration->module_name);
        
        if ($module) {
            return $this->integrationRepository->createOrUpdate($integration->module_name, [
                'name' => $module->getName(),
                'description' => $module->getDescription(),
                'is_enabled' => $module->isEnabled(),
                'is_configured' => $module->isConfigured(),
            ]);
        }
        
        return $integration;
    }
} 