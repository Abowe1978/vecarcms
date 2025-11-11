@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Stripe Configuration</h1>
            <p class="text-gray-600 mt-1">Configure your Stripe payment processing integration</p>
        </div>
        <a href="{{ route('admin.integrations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Back to Integrations
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Status -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-medium">Status</h3>
            </div>
            <div class="flex gap-2">
                @if($integration->is_enabled)
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Enabled</span>
                    <form action="{{ route('admin.stripe.disable') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Disable
                        </button>
                    </form>
                @else
                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Disabled</span>
                    <form action="{{ route('admin.stripe.enable') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                            Enable
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">Configured:</span>
            @if($integration->is_configured)
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Yes</span>
            @else
                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">Not Configured</span>
            @endif
        </div>
    </div>

    <!-- Configuration Form -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-medium mb-4">API Keys</h3>
        
        <form action="{{ route('admin.stripe.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Public Key -->
            <div class="mb-4">
                <label for="public_key" class="block text-sm font-medium text-gray-700 mb-2">
                    Publishable Key <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="public_key" 
                    id="public_key" 
                    value="{{ old('public_key', $integration->config['public_key'] ?? '') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="pk_test_..."
                    required
                >
                <p class="mt-1 text-sm text-gray-500">Your Stripe publishable key (starts with pk_test_ or pk_live_)</p>
            </div>

            <!-- Secret Key -->
            <div class="mb-4">
                <label for="secret_key" class="block text-sm font-medium text-gray-700 mb-2">
                    Secret Key <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="secret_key" 
                    id="secret_key" 
                    value="{{ old('secret_key', $integration->config['secret_key'] ?? '') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="sk_test_..."
                    required
                >
                <p class="mt-1 text-sm text-gray-500">Your Stripe secret key (starts with sk_test_ or sk_live_)</p>
            </div>

            <!-- Webhook Secret -->
            <div class="mb-4">
                <label for="webhook_secret" class="block text-sm font-medium text-gray-700 mb-2">
                    Webhook Signing Secret
                </label>
                <input 
                    type="password" 
                    name="webhook_secret" 
                    id="webhook_secret" 
                    value="{{ old('webhook_secret', $integration->config['webhook_secret'] ?? '') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="whsec_..."
                >
                <p class="mt-1 text-sm text-gray-500">Your Stripe webhook endpoint secret (optional but recommended)</p>
            </div>

            <!-- Webhook URL Info -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="text-sm font-medium text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-1"></i> Webhook Configuration
                </h4>
                <p class="text-sm text-blue-800 mb-2">
                    Configure this URL in your Stripe Dashboard:
                </p>
                <code class="block bg-blue-100 text-blue-900 px-3 py-2 rounded text-sm">
                    {{ url('/stripe/webhook') }}
                </code>
                <p class="text-xs text-blue-700 mt-2">
                    Events to listen: checkout.session.completed, invoice.paid, customer.subscription.*
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.integrations.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i>Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

