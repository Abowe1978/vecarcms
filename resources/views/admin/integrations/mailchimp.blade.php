@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Mailchimp Configuration</h1>
            <p class="text-gray-600 mt-1">Configure your Mailchimp email marketing integration</p>
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
                    <form action="{{ route('admin.mailchimp.disable') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Disable
                        </button>
                    </form>
                @else
                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Disabled</span>
                    <form action="{{ route('admin.mailchimp.enable') }}" method="POST" class="inline">
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
        <h3 class="text-lg font-medium mb-4">API Configuration</h3>
        
        <form action="{{ route('admin.mailchimp.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- API Key -->
            <div class="mb-4">
                <label for="api_key" class="block text-sm font-medium text-gray-700 mb-2">
                    API Key <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="api_key" 
                    id="api_key" 
                    value="{{ old('api_key', $integration->config['api_key'] ?? '') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter your Mailchimp API key"
                    required
                >
                <p class="mt-1 text-sm text-gray-500">
                    Get your API key from: 
                    <a href="https://admin.mailchimp.com/account/api/" target="_blank" class="text-blue-600 hover:underline">
                        Mailchimp Account → API Keys
                    </a>
                </p>
            </div>

            <!-- List ID -->
            <div class="mb-4">
                <label for="list_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Audience (List) ID <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="list_id" 
                    id="list_id" 
                    value="{{ old('list_id', $integration->config['list_id'] ?? '') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter your Mailchimp List ID"
                    required
                >
                <p class="mt-1 text-sm text-gray-500">
                    Find your List ID in: Mailchimp → Audience → Settings → Audience name and defaults
                </p>
            </div>

            <!-- Double Opt-in -->
            <div class="mb-4">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="double_optin" 
                        id="double_optin" 
                        value="1"
                        {{ old('double_optin', $integration->config['double_optin'] ?? false) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="double_optin" class="ml-2 block text-sm text-gray-900">
                        Enable Double Opt-in
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500 ml-6">
                    When enabled, subscribers will receive a confirmation email before being added to your list
                </p>
            </div>

            <!-- Auto Add Members -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="auto_add_members" 
                        id="auto_add_members" 
                        value="1"
                        {{ old('auto_add_members', $integration->config['auto_add_members'] ?? true) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="auto_add_members" class="ml-2 block text-sm text-gray-900">
                        Automatically add new members to Mailchimp
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500 ml-6">
                    When enabled, new user registrations will be automatically synced to Mailchimp
                </p>
            </div>

            <!-- Webhook Info -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="text-sm font-medium text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-1"></i> Webhook Configuration
                </h4>
                <p class="text-sm text-blue-800 mb-2">
                    Configure this URL in your Mailchimp Webhook settings:
                </p>
                <code class="block bg-blue-100 text-blue-900 px-3 py-2 rounded text-sm">
                    {{ url('/mailchimp/webhook') }}
                </code>
                <p class="text-xs text-blue-700 mt-2">
                    Events to listen: Subscribe, Unsubscribe, Profile Update, Email Changed
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

