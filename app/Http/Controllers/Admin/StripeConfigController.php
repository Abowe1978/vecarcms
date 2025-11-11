<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use Illuminate\Http\Request;

class StripeConfigController extends Controller
{
    /**
     * Show Stripe configuration form
     */
    public function show()
    {
        $integration = Integration::where('module_name', 'Stripe')->first();
        
        if (!$integration) {
            $integration = Integration::create([
                'module_name' => 'Stripe',
                'name' => 'Stripe',
                'description' => 'Stripe payment processing',
                'is_enabled' => false,
                'is_configured' => false,
                'config' => [],
            ]);
        }
        
        return view('admin.integrations.stripe', compact('integration'));
    }

    /**
     * Update Stripe configuration
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
            'webhook_secret' => 'nullable|string',
        ]);

        $integration = Integration::where('module_name', 'Stripe')->first();
        
        if (!$integration) {
            return redirect()->back()->with('error', 'Stripe integration not found');
        }

        $integration->update([
            'config' => $validated,
            'is_configured' => true,
        ]);

        return redirect()->back()->with('success', 'Stripe configuration saved successfully!');
    }

    /**
     * Enable Stripe
     */
    public function enable()
    {
        $integration = Integration::where('module_name', 'Stripe')->first();
        
        if ($integration) {
            $integration->update(['is_enabled' => true]);
        }

        return redirect()->back()->with('success', 'Stripe enabled successfully!');
    }

    /**
     * Disable Stripe
     */
    public function disable()
    {
        $integration = Integration::where('module_name', 'Stripe')->first();
        
        if ($integration) {
            $integration->update(['is_enabled' => false]);
        }

        return redirect()->back()->with('success', 'Stripe disabled successfully!');
    }
}
