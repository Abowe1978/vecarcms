<?php

namespace App\Repositories\Interfaces;

use App\Models\Integration;
use Illuminate\Support\Collection;

interface IntegrationRepositoryInterface
{
    public function findByModuleName(string $name): ?Integration;
    
    public function createOrUpdate(string $moduleName, array $data): Integration;
    
    public function updateConfig(Integration $integration, array $config): bool;
    
    public function updateStatus(Integration $integration, bool $isEnabled): bool;
    
    public function getAllIntegrations(): Collection;
} 