<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TaxonomyRegistryService;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register TaxonomyRegistry as singleton
        $this->app->singleton(TaxonomyRegistryService::class, function ($app) {
            return new TaxonomyRegistryService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Taxonomies are registered in the TaxonomyRegistryService constructor
        // Plugins and themes can register additional taxonomies using:
        // register_taxonomy('product_category', [...]);
    }
}

