@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-800">{{ __('admin.integrations.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('admin.integrations.description') }}</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.integrations.name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.integrations.description') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.integrations.status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.integrations.configured') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.integrations.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($integrations as $integration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $integration['name'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        {{ $integration['description'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $integration['is_enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $integration['is_enabled'] ? __('admin.integrations.enabled') : __('admin.integrations.disabled') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $integration['is_configured'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $integration['is_configured'] ? __('admin.integrations.configured') : __('admin.integrations.not_configured') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if ($integration['is_enabled'])
                                            <a href="{{ route('admin.integrations.disable', $integration['model']->module_name) }}" 
                                               class="text-red-600 hover:text-red-900 bg-red-100 px-3 py-1 rounded hover:bg-red-200 transition">
                                                {{ __('admin.integrations.disable') }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin.integrations.enable', $integration['model']->module_name) }}" 
                                               class="text-green-600 hover:text-green-900 bg-green-100 px-3 py-1 rounded hover:bg-green-200 transition">
                                                {{ __('admin.integrations.enable') }}
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('admin.integrations.show', $integration['model']->module_name) }}" 
                                           class="text-blue-600 hover:text-blue-900 bg-blue-100 px-3 py-1 rounded hover:bg-blue-200 transition">
                                            {{ __('admin.integrations.configure') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ __('admin.integrations.no_integrations') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 