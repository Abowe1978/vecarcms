<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeService;
use App\Services\ThemeInstallerService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ThemeController extends Controller
{
    public function __construct(
        protected ThemeService $themeService,
        protected ThemeInstallerService $themeInstallerService
    ) {}

    /**
     * Display all themes
     */
    public function index(): View
    {
        $themes = $this->themeService->all();
        $activeTheme = $this->themeService->getActiveTheme();

        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Activate a theme
     */
    public function activate(Theme $theme): RedirectResponse
    {
        $this->themeService->activateTheme($theme);

        return back()->with('success', "Theme '{$theme->display_name}' activated successfully!");
    }

    /**
     * Scan themes directory for new themes
     */
    public function scan(): RedirectResponse
    {
        $count = $this->themeService->scanThemes();

        return back()->with('success', "{$count} new theme(s) discovered!");
    }

    /**
     * Show theme settings
     */
    public function settings(Theme $theme): View
    {
        return view('admin.themes.settings', compact('theme'));
    }

    /**
     * Update theme settings
     */
    public function updateSettings(Request $request, Theme $theme): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => 'array',
        ]);

        $this->themeService->updateSettings($theme, $validated['settings'] ?? []);

        return back()->with('success', 'Theme settings updated successfully!');
    }

    /**
     * Upload and install theme from ZIP (WordPress-like)
     */
    public function upload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:51200', // Max 50MB
        ]);

        try {
            $theme = $this->themeInstallerService->installFromZip($validated['theme_zip']);
            
            return redirect()
                ->route('admin.themes.index')
                ->with('success', "Theme '{$theme->display_name}' installed successfully! Version {$theme->version}");
        } catch (\Exception $e) {
            return back()->with('error', 'Theme installation failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a theme
     */
    public function destroy(Theme $theme): RedirectResponse
    {
        try {
            $this->themeInstallerService->uninstallTheme($theme);
            
            return redirect()
                ->route('admin.themes.index')
                ->with('success', "Theme '{$theme->display_name}' uninstalled successfully!");
                
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

