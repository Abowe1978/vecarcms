<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Services\PluginService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PluginController extends Controller
{
    public function __construct(
        protected PluginService $pluginService
    ) {
        $this->middleware('permission:manage_plugins');
    }

    /**
     * Display all plugins
     */
    public function index(): View
    {
        $plugins = $this->pluginService->all();
        
        return view('admin.plugins.index', compact('plugins'));
    }

    /**
     * Scan plugins directory
     */
    public function scan(): RedirectResponse
    {
        $count = $this->pluginService->scanPlugins();
        
        return back()->with('success', "{$count} plugin(s) trovati!");
    }

    /**
     * Activate a plugin
     */
    public function activate(Plugin $plugin): RedirectResponse
    {
        $this->pluginService->activate($plugin);
        
        return back()->with('success', "Plugin '{$plugin->name}' attivato!");
    }

    /**
     * Deactivate a plugin
     */
    public function deactivate(Plugin $plugin): RedirectResponse
    {
        $this->pluginService->deactivate($plugin);
        
        return back()->with('success', "Plugin '{$plugin->name}' disattivato!");
    }

    /**
     * Delete a plugin
     */
    public function destroy(Plugin $plugin): RedirectResponse
    {
        $name = $plugin->name;
        $this->pluginService->delete($plugin);
        
        return back()->with('success', "Plugin '{$name}' eliminato!");
    }

    /**
     * Upload and install plugin from ZIP
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'plugin_zip' => 'required|file|mimes:zip|max:10240', // 10MB max
        ]);

        $result = $this->pluginService->installFromZip($request->file('plugin_zip'));

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Show plugin settings
     */
    public function settings(Plugin $plugin): View
    {
        return view('admin.plugins.settings', compact('plugin'));
    }

    /**
     * Update plugin settings
     */
    public function updateSettings(Request $request, Plugin $plugin): RedirectResponse
    {
        $validated = $request->validate([
            'config' => 'array',
        ]);

        $this->pluginService->updateConfig($plugin, $validated['config'] ?? []);

        return back()->with('success', 'Impostazioni plugin aggiornate!');
    }
}

