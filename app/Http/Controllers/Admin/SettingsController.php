<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    /**
     * Display settings grouped by category
     */
    public function index(): View
    {
        $settings = $this->settingsService->getAllGrouped();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        $this->settingsService->updateSettings($validated['settings']);

        return back()->with('success', 'Settings updated successfully!');
    }
}

