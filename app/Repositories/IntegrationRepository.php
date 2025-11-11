<?php

namespace App\Repositories;

use App\Models\Integration;
use App\Repositories\Interfaces\IntegrationRepositoryInterface;
use Illuminate\Support\Collection;

class IntegrationRepository implements IntegrationRepositoryInterface
{
    public function findByModuleName(string $name): ?Integration
    {
        return Integration::firstOrNew(['module_name' => $name]);
    }
    
    public function createOrUpdate(string $moduleName, array $data): Integration
    {
        $integration = $this->findByModuleName($moduleName);
        $integration->fill($data);
        $integration->save();
        
        return $integration;
    }
    
    public function updateConfig(Integration $integration, array $config): bool
    {
        $integration->config = $config;
        return $integration->save();
    }
    
    public function updateStatus(Integration $integration, bool $isEnabled): bool
    {
        $integration->is_enabled = $isEnabled;
        return $integration->save();
    }
    
    public function getAllIntegrations(): Collection
    {
        return Integration::all();
    }
} 