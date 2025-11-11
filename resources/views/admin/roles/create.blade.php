@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ __('admin.roles.create_role') }}
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-5 w-5"></i>
                {{ __('admin.roles.back_to_roles') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="px-4 py-5 sm:p-6">
                <!-- Role Name -->
                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            {{ __('admin.roles.role_name') }}
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                   value="{{ old('name') }}" 
                                   required>
                        </div>
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Permissions -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        {{ __('admin.roles.permissions') }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}" 
                                       id="permission_{{ $permission->id }}" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="permission_{{ $permission->id }}" class="font-medium text-gray-700">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('permissions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('admin.roles.create_role') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 