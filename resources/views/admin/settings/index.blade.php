@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Settings</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-700 capitalize">{{ $group }} Settings</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    @foreach($groupSettings as $setting)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            
                            @if($setting->description)
                                <p class="text-xs text-gray-500 mb-2">{{ $setting->description }}</p>
                            @endif
                            
                            @if($setting->type === 'boolean')
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[{{ $setting->key }}]" 
                                           value="1"
                                           {{ $setting->value ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200">
                                    <span class="ml-2 text-sm text-gray-600">Enable</span>
                                </label>
                            @elseif($setting->type === 'text' || $setting->type === 'integer')
                                <input type="text" 
                                       name="settings[{{ $setting->key }}]" 
                                       value="{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            @elseif($setting->type === 'array' || $setting->type === 'json')
                                <textarea name="settings[{{ $setting->key }}]" 
                                          rows="5"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 font-mono text-sm">{{ $setting->getRawOriginal('value') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">JSON format</p>
                            @else
                                <textarea name="settings[{{ $setting->key }}]" 
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">{{ is_string($setting->value) ? $setting->value : $setting->getRawOriginal('value') }}</textarea>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>
@endsection

