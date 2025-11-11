<?php

namespace App\Services\Interfaces;

use App\Models\Integration;
use Illuminate\Support\Collection;

interface IntegrationServiceInterface
{
    public function getAllIntegrations(): Collection;
    
    public function getIntegration(string $name): ?array;
    
    public function enableIntegration(string $name): bool;
    
    public function disableIntegration(string $name): bool;
    
    public function updateIntegrationConfig(string $name, array $config): bool;
    
    public function syncIntegrationWithModule(Integration $integration): Integration;
} 