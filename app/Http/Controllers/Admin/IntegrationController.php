<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IntegrationServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateIntegrationConfigRequest;

class IntegrationController extends Controller
{
    protected IntegrationServiceInterface $integrationService;

    public function __construct(IntegrationServiceInterface $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * Display a listing of the integrations.
     */
    public function index()
    {
        $integrations = $this->integrationService->getAllIntegrations();
        return view('admin.integrations.index', compact('integrations'));
    }

    /**
     * Show the form for configuring the specified integration.
     */
    public function show($name)
    {
        // Redirect Stripe to its dedicated configuration page
        if ($name === 'Stripe') {
            return redirect()->route('admin.stripe.config');
        }
        
        // Redirect Mailchimp to its dedicated configuration page  
        if ($name === 'Mailchimp') {
            return redirect()->route('admin.mailchimp.config');
        }
        
        $data = $this->integrationService->getIntegration($name);
        
        if (!$data) {
            return redirect()->route('admin.integrations.index')
                ->with('error', __('admin.integrations.not_found'));
        }
        
        return view('admin.integrations.show', [
            'module' => $data['module'],
            'integration' => $data['integration'],
            'config_fields' => $data['config_fields'],
            'config' => $data['config']
        ]);
    }

    /**
     * Enable an integration
     */
    public function enable($name)
    {
        $success = $this->integrationService->enableIntegration($name);
        
        if (!$success) {
            return redirect()->route('admin.integrations.index')
                ->with('error', __('admin.integrations.not_found'));
        }
        
        return redirect()->route('admin.integrations.show', $name)
            ->with('success', __('admin.integrations.enabled'));
    }

    /**
     * Disable an integration
     */
    public function disable($name)
    {
        $success = $this->integrationService->disableIntegration($name);
        
        if (!$success) {
            return redirect()->route('admin.integrations.index')
                ->with('error', __('admin.integrations.not_found'));
        }
        
        return redirect()->route('admin.integrations.show', $name)
            ->with('success', __('admin.integrations.disabled'));
    }
    
    /**
     * Update the configuration for a module
     */
    public function update(UpdateIntegrationConfigRequest $request, $name)
    {
        $module = app(\App\Modules\Core\ModuleManager::class)->get($name);
        $fields = $module ? $module->getConfigFields() : [];
        $inputConfig = $request->input('config', []);
        $cleanConfig = [];

        foreach ($fields as $field => $options) {
            if (array_key_exists($field, $inputConfig)) {
                $cleanConfig[$field] = $inputConfig[$field];
            } elseif (isset($options['default'])) {
                $cleanConfig[$field] = $options['default'];
            } else {
                $cleanConfig[$field] = $options['type'] === 'checkbox' ? false : null;
            }
        }

        $success = $this->integrationService->updateIntegrationConfig($name, $cleanConfig);

        if (!$success) {
            return redirect()->route('admin.integrations.show', $name)
                ->with('error', __('admin.integrations.update_error'));
        }

        return redirect()->route('admin.integrations.show', $name)
            ->with('success', __('admin.integrations.updated'));
    }
} 