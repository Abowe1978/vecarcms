<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use Illuminate\Http\Request;

class MailchimpConfigController extends Controller
{
    /**
     * Show Mailchimp configuration form
     */
    public function show()
    {
        $integration = Integration::where('module_name', 'Mailchimp')->first();
        
        if (!$integration) {
            $integration = Integration::create([
                'module_name' => 'Mailchimp',
                'name' => 'Mailchimp',
                'description' => 'Mailchimp email marketing',
                'is_enabled' => false,
                'is_configured' => false,
                'config' => [],
            ]);
        }
        
        return view('admin.integrations.mailchimp', compact('integration'));
    }

    /**
     * Update Mailchimp configuration
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'list_id' => 'required|string',
            'double_optin' => 'nullable|boolean',
            'auto_add_members' => 'nullable|boolean',
        ]);

        $integration = Integration::where('module_name', 'Mailchimp')->first();
        
        if (!$integration) {
            return redirect()->back()->with('error', 'Mailchimp integration not found');
        }

        $integration->update([
            'config' => [
                'api_key' => $validated['api_key'],
                'list_id' => $validated['list_id'],
                'double_optin' => $validated['double_optin'] ?? false,
                'auto_add_members' => $validated['auto_add_members'] ?? false,
            ],
            'is_configured' => true,
        ]);

        return redirect()->back()->with('success', 'Mailchimp configuration saved successfully!');
    }

    /**
     * Enable Mailchimp
     */
    public function enable()
    {
        $integration = Integration::where('module_name', 'Mailchimp')->first();
        
        if ($integration) {
            $integration->update(['is_enabled' => true]);
        }

        return redirect()->back()->with('success', 'Mailchimp enabled successfully!');
    }

    /**
     * Disable Mailchimp
     */
    public function disable()
    {
        $integration = Integration::where('module_name', 'Mailchimp')->first();
        
        if ($integration) {
            $integration->update(['is_enabled' => false]);
        }

        return redirect()->back()->with('success', 'Mailchimp disabled successfully!');
    }
}
