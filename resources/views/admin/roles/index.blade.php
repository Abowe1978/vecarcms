@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('admin.roles.title') }}</h1>
        <div class="mt-4 sm:mt-0 space-x-3">
            <a href="{{ route('admin.permissions.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus -ml-1 mr-2 h-5 w-5 text-gray-500"></i>
                {{ __('admin.roles.create_permission') }}
            </a>
            <a href="{{ route('admin.roles.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus -ml-1 mr-2 h-5 w-5"></i>
                {{ __('admin.roles.create_role') }}
            </a>
        </div>
    </div>

    <!-- Roles List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($roles as $role)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-blue-600 truncate">
                                {{ $role->name }}
                            </div>
                            @if($role->is_hidden)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ __('admin.roles.hidden') }}
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.roles.edit', $role) }}" 
                               class="inline-flex items-center p-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($role->name !== 'developer')
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('{{ __('admin.roles.delete_role_confirm') }}')"
                                        class="inline-flex items-center p-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-sm text-gray-500">
                            {{ __('admin.roles.permissions') }}:
                            <div class="mt-1 flex flex-wrap gap-2">
                                @foreach($role->permissions as $permission)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $permission->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Permissions List -->
    <div class="mt-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.roles.all_permissions') }}</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($permissions->chunk(3) as $chunk)
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach($chunk as $permission)
                        <div class="flex items-center justify-between bg-gray-50 px-4 py-2 rounded-md flex-1">
                            <span class="text-sm text-gray-900">{{ $permission->name }}</span>
                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('{{ __('admin.roles.delete_permission_confirm') }}')"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection 