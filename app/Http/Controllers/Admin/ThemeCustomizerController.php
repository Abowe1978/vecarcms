<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ThemeCustomizerController extends Controller
{
    public function __construct(
        protected ThemeService $themeService
    ) {
        $this->middleware('permission:manage_themes');
    }

    /**
     * Show theme customizer
     */
    public function index(Theme $theme): View
    {
        $settings = $theme->settings ? json_decode($theme->settings, true) : [];
        
        return view('admin.themes.customizer', compact('theme', 'settings'));
    }

    /**
     * Update theme settings (AJAX)
     */
    public function update(Request $request, Theme $theme): JsonResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        $this->themeService->updateSettings($theme, $validated['settings']);

        return response()->json([
            'success' => true,
            'message' => 'Theme settings updated successfully!',
        ]);
    }

    /**
     * Reset theme settings to defaults
     */
    public function reset(Theme $theme): RedirectResponse
    {
        $this->themeService->resetSettings($theme);

        return back()->with('success', 'Theme settings reset to defaults!');
    }
}

