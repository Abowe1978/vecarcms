<?php

namespace Modules\Stripe\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Stripe\app\Services\Interfaces\StripeServiceInterface;
use Modules\Stripe\app\Http\Requests\UpdateStripeSettingsRequest;

class StripeSettingsController extends Controller
{
    protected $stripeService;

    public function __construct(StripeServiceInterface $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Show the Stripe settings form
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $config = $this->stripeService->getConfig();
        return view('stripe::admin.settings', compact('config'));
    }

    /**
     * Update the Stripe settings
     *
     * @param UpdateStripeSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStripeSettingsRequest $request)
    {
        $success = $this->stripeService->updateConfig($request->validated());

        if ($success) {
            return redirect()
                ->route('admin.stripe.settings')
                ->with('success', __('stripe::messages.settings_updated'));
        }

        return redirect()
            ->route('admin.stripe.settings')
            ->with('error', __('stripe::messages.settings_update_failed'));
    }
} 