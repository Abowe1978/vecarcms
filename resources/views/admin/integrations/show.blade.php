@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">{{ $module->getName() }}</h1>
                <p class="mt-2 text-gray-600">{{ $module->getDescription() }}</p>
            </div>
            <div>
                <a href="{{ route('admin.integrations.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 transition">
                    {{ __('admin.integrations.back_to_list') }}
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="font-semibold">{{ __('admin.integrations.status') }}:</div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $module->isEnabled() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $module->isEnabled() ? __('admin.integrations.enabled') : __('admin.integrations.disabled') }}
                    </span>
                    <div>
                        @if ($module->isEnabled())
                            <a href="{{ route('admin.integrations.disable', $module->getName()) }}" 
                               class="text-red-600 hover:text-red-900 bg-red-100 px-3 py-1 rounded hover:bg-red-200 transition">
                                {{ __('admin.integrations.disable') }}
                            </a>
                        @else
                            <a href="{{ route('admin.integrations.enable', $module->getName()) }}" 
                               class="text-green-600 hover:text-green-900 bg-green-100 px-3 py-1 rounded hover:bg-green-200 transition">
                                {{ __('admin.integrations.enable') }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-4 mb-4">
                    <div class="font-semibold">{{ __('admin.integrations.configured') }}:</div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $module->isConfigured() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $module->isConfigured() ? __('admin.integrations.configured') : __('admin.integrations.not_configured') }}
                    </span>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">{{ __('admin.integrations.configuration') }}</h2>
                
                <form action="{{ route('admin.integrations.update', $module->getName()) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <!-- Form fields for configuration -->
                    <div class="grid grid-cols-1 gap-6 mt-4">
                        @php
                            $config = $module->getConfig();
                            $config_fields = $module->getConfigFields();
                        @endphp
                        
                        @if(!empty($config_fields))
                            @foreach($config_fields as $field => $options)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">
                                        {{ $options['label'] ?? ucfirst(str_replace('_', ' ', $field)) }}
                                    </label>
                                    
                                    @if($options['type'] == 'text' || $options['type'] == 'password')
                                        <input type="{{ $options['type'] }}" 
                                               name="config[{{ $field }}]" 
                                               id="{{ $field }}" 
                                               value="{{ old('config.'.$field, $config[$field] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               @if(!empty($options['placeholder'])) placeholder="{{ $options['placeholder'] }}" @endif
                                               @if(!empty($options['required'])) required @endif>
                                    @elseif($options['type'] == 'textarea')
                                        <textarea name="config[{{ $field }}]" 
                                                  id="{{ $field }}" 
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                  rows="3"
                                                  @if(!empty($options['required'])) required @endif>{{ old('config.'.$field, $config[$field] ?? '') }}</textarea>
                                    @elseif($options['type'] == 'select')
                                        <select name="config[{{ $field }}]" 
                                                id="{{ $field }}" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                @if(!empty($options['required'])) required @endif>
                                            @foreach($options['options'] as $value => $label)
                                                <option value="{{ $value }}" {{ (old('config.'.$field, $config[$field] ?? '') == $value) ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif($options['type'] == 'checkbox')
                                        <div class="mt-1">
                                            <input type="checkbox" 
                                                   name="config[{{ $field }}]" 
                                                   id="{{ $field }}" 
                                                   value="1"
                                                   {{ old('config.'.$field, $config[$field] ?? false) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <label for="{{ $field }}" class="ml-2 text-sm text-gray-700">
                                                {{ $options['checkbox_label'] ?? '' }}
                                            </label>
                                        </div>
                                    @endif
                                    
                                    @if(!empty($options['help']))
                                        <p class="mt-1 text-sm text-gray-500">{{ $options['help'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="bg-yellow-100 p-4 rounded-md">
                                <p class="text-yellow-800">{{ __('admin.integrations.no_config_fields') }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ __('admin.integrations.save_config') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 