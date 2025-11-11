<?php

namespace App\Providers;

use App\Models\Integration;
use App\Modules\Core\ModuleManager;
use App\Modules\Core\IntegrationRegistry;
use App\Modules\Core\IntegrationRegistryInterface;
use Illuminate\Support\ServiceProvider;

class IntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the ModuleManager as a singleton
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager();
        });
        
        // Register the IntegrationRegistry binding
        $this->app->bind(IntegrationRegistryInterface::class, IntegrationRegistry::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            /** @var ModuleManager $manager */
            $manager = $this->app->make(ModuleManager::class);

            // Possiamo registrare i moduli qui oppure nei singoli service provider dei moduli
            
            // Sync module configurations from database
            $manager->syncWithDatabase();
        });
    }
} 