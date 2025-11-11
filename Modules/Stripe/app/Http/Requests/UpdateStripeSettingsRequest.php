<?php

namespace Modules\Stripe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStripeSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage_stripe_settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255'],
            'secret' => ['required', 'string', 'max:255'],
            'webhook_secret' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'size:3'],
            'sandbox' => ['boolean'],
            'statement_descriptor' => ['required', 'string', 'max:22', 'regex:/^[^<>"\'*]*$/'],
            'automatic_tax' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'key.required' => __('stripe::validation.key_required'),
            'secret.required' => __('stripe::validation.secret_required'),
            'webhook_secret.required' => __('stripe::validation.webhook_secret_required'),
            'currency.required' => __('stripe::validation.currency_required'),
            'currency.size' => __('stripe::validation.currency_size'),
            'statement_descriptor.required' => __('stripe::validation.statement_descriptor_required'),
            'statement_descriptor.max' => __('stripe::validation.statement_descriptor_max'),
            'statement_descriptor.regex' => __('stripe::validation.statement_descriptor_invalid'),
        ];
    }
} 