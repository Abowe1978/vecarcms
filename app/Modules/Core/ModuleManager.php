<?php

namespace App\Modules\Core;

use App\Models\Integration;
use App\Modules\Core\Interfaces\IntegrationModuleInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ModuleManager
{
    private array $modules = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scanModules();
    }

    /**
     * Scan the Modules directory and register available modules
     */
    public function scanModules(): void
    {
        $modulesPath = base_path('Modules');
        
        if (!File::isDirectory($modulesPath)) {
            Log::warning('Modules directory not found at: ' . $modulesPath);
            return;
        }

        $moduleDirectories = File::directories($modulesPath);

        foreach ($moduleDirectories as $moduleDir) {
            $jsonPath = $moduleDir . '/module.json';
            
            if (!File::exists($jsonPath)) {
                Log::warning("No module.json found in: " . $moduleDir);
                continue;
            }

            try {
                $config = json_decode(File::get($jsonPath), true);

                if (!isset($config['name']) || !isset($config['providers'])) {
                    Log::warning("Invalid module.json in: " . $moduleDir . ". Missing required fields.");
                    continue;
                }

                // Don't override manually registered modules
                if (isset($this->modules[$config['name']])) {
                    Log::info("Module {$config['name']} already registered manually, skipping automatic registration");
                    continue;
                }

                $this->modules[$config['name']] = new class($config) implements IntegrationModuleInterface {
                    private array $config;

                    public function __construct(array $config)
                    {
                        $this->config = $config;
                    }

                    public function getName(): string
                    {
                        return $this->config['name'];
                    }

                    public function getDescription(): string
                    {
                        return $this->config['description'] ?? '';
                    }

                    public function isEnabled(): bool
                    {
                        return Integration::where('module_name', $this->getName())
                            ->value('is_enabled') ?? false;
                    }

                    public function isConfigured(): bool
                    {
                        return Integration::where('module_name', $this->getName())
                            ->value('is_configured') ?? false;
                    }

                    public function getConfig(): array
                    {
                        return Integration::where('module_name', $this->getName())
                            ->value('config') ?? [];
                    }

                    public function enable(): bool
                    {
                        return true;
                    }

                    public function disable(): bool
                    {
                        return true;
                    }

                    public function updateConfig(array $config): bool
                    {
                        return true;
                    }

                    public function getConfigFields(): array
                    {
                        return [];
                    }
                };

            } catch (\Exception $e) {
                Log::error("Error loading module from: " . $moduleDir . ". " . $e->getMessage());
            }
        }
    }

    /**
     * Register a module
     *
     * @param IntegrationModuleInterface $module
     * @return void
     */
    public function register(IntegrationModuleInterface $module): void
    {
        $moduleName = $module->getName();
        
        // Check if module already exists (from auto-discovery)
        if (isset($this->modules[$moduleName])) {
            $existingModule = $this->modules[$moduleName];
            $existingClass = get_class($existingModule);
            
            // If the existing module is the auto-discovered one (anonymous class), replace it
            if (str_contains($existingClass, '@anonymous')) {
                Log::info("Replacing auto-discovered module '{$moduleName}' with manually registered module: " . get_class($module));
                $this->modules[$moduleName] = $module;
            } else {
                Log::info("Module '{$moduleName}' already registered manually, skipping registration");
                return;
            }
        } else {
            $this->modules[$moduleName] = $module;
        }
        
        try {
            // Ensure the module exists in the database
            $integration = Integration::firstOrNew(['module_name' => $moduleName]);
            
            if (!$integration->exists) {
                $integration->fill([
                    'name' => $module->getName(),
                    'description' => $module->getDescription(),
                    'is_enabled' => $module->isEnabled(),
                    'is_configured' => $module->isConfigured(),
                ]);
                $integration->save();
            }
        } catch (\Exception $e) {
            Log::error('Error registering module ' . $moduleName . ': ' . $e->getMessage());
        }
    }

    /**
     * Get all modules
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
    public function get(string $name): ?IntegrationModuleInterface
    {
        return $this->modules[$name] ?? null;
    }

    /**
     * Check if a module exists
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->modules[$name]);
    }

    /**
     * Get all enabled modules
     *
     * @return Collection
     */
    public function enabled(): Collection
    {
        return collect($this->modules)->filter(function (IntegrationModuleInterface $module) {
            return $module->isEnabled();
        });
    }

    /**
     * Get all configured modules
     *
     * @return Collection
     */
    public function configured(): Collection
    {
        return collect($this->modules)->filter(function (IntegrationModuleInterface $module) {
            return $module->isConfigured();
        });
    }

    /**
     * Get all active modules (enabled and configured)
     *
     * @return Collection
     */
    public function active(): Collection
    {
        return collect($this->modules)->filter(function (IntegrationModuleInterface $module) {
            return $module->isEnabled() && $module->isConfigured();
        });
    }

    /**
     * Sync module configurations with database
     *
     * @return void
     */
    public function syncWithDatabase(): void
    {
        try {
            $integrations = Integration::all();

            foreach ($integrations as $integration) {
                $module = $this->get($integration->module_name);
                
                if ($module) {
                    // Update module configuration
                    if ($integration->config) {
                        $module->updateConfig($integration->config);
                    }
                    
                    // Update enabled status
                    if ($integration->is_enabled) {
                        $module->enable();
                    } else {
                        $module->disable();
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error syncing module configurations: ' . $e->getMessage());
        }
    }

    /**
     * Get all modules as a Collection
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return collect($this->modules);
    }

    public function hasModule(string $name): bool
    {
        return isset($this->modules[$name]);
    }

    public function getModule(string $name): ?IntegrationModuleInterface
    {
        return $this->modules[$name] ?? null;
    }
} 