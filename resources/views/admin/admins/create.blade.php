@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.admins.create') }}</h1>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
            @csrf
            
            <!-- Profile Image Section -->
            <div class="px-4 py-5 sm:p-6 bg-gray-50">
                <div class="flex flex-col md:flex-row items-start md:items-center">
                    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-100">
                                <div class="flex items-center justify-center w-full h-full bg-blue-50 text-blue-500">
                                    <i class="fas fa-user text-4xl"></i>
                                </div>
                            </div>
                            <label for="profile_image" class="absolute inset-0 flex items-center justify-center w-full h-full bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 rounded-full transition-opacity duration-200 cursor-pointer">
                                <span class="text-white text-sm font-medium"><i class="fas fa-camera mr-1"></i> {{ __('admin.admins.upload') }}</span>
                            </label>
                            <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
                        </div>
                        @error('profile_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('admin.admins.profile_information') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('admin.admins.create_description') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Profile Details Section -->
            <div class="px-4 py-5 sm:p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            {{ __('admin.admins.name') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="John"
                                   required>
                        </div>
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700">
                            {{ __('admin.admins.last_name') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="surname" 
                                   id="surname" 
                                   class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('surname') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                   value="{{ old('surname') }}" 
                                   placeholder="Doe"
                                   required>
                        </div>
                        @error('surname')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            {{ __('admin.admins.email') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="johndoe@example.com"
                                   required>
                        </div>
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            {{ __('admin.admins.password') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                   required>
                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            {{ __('admin.admins.password_confirmation') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        {{ __('admin.admins.role') }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $adminRoles = ['super-admin', 'admin'];
                        @endphp
                        
                        @foreach($roles as $role)
                            @if(in_array($role->name, $adminRoles))
                            <div class="relative flex group">
                                <input type="radio" 
                                       name="role" 
                                       value="{{ $role->name }}" 
                                       id="role_{{ $role->id }}" 
                                       class="hidden"
                                       {{ old('role') === $role->name ? 'checked' : '' }}
                                       required>
                                <label for="role_{{ $role->id }}" 
                                       class="flex flex-1 p-4 border rounded-lg cursor-pointer focus:outline-none transition-all duration-200
                                       hover:bg-red-50 hover:border-red-500 hover:ring-2 hover:ring-red-500
                                       {{ old('role') === $role->name 
                                          ? 'bg-red-50 border-red-500 ring-2 ring-red-500'
                                          : 'bg-white border-gray-200' }}">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 mr-4 flex items-center justify-center rounded-full 
                                            {{ $role->name == 'super-admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            @if($role->name == 'super-admin')
                                                <i class="fas fa-shield-alt"></i>
                                            @elseif($role->name == 'admin')
                                                <i class="fas fa-user-cog"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ ucfirst($role->name) }}</p>
                                            <p class="text-sm text-gray-500">
                                                @if($role->name == 'super-admin')
                                                    {{ __('admin.roles.super_admin_description') }}
                                                @elseif($role->name == 'admin')
                                                    {{ __('admin.roles.admin_description') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-4 py-4 sm:px-6 flex justify-end space-x-3 bg-gray-50">
                <a href="{{ route('admin.admins.index') }}" 
                   class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('admin.admins.back_to_admins') }}
                </a>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> {{ __('admin.admins.create') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('profile_image');
        const placeholderIcon = imageInput.closest('.relative').querySelector('.fas.fa-user');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create a new image element
                    const newImage = document.createElement('img');
                    newImage.src = e.target.result;
                    newImage.className = 'w-full h-full object-cover';
                    newImage.alt = 'Profile Preview';
                    
                    // Replace the placeholder with the new image
                    placeholderIcon.parentNode.replaceWith(newImage);
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Aggiungi la gestione dei ruoli
        const roleInputs = document.querySelectorAll('input[name="role"]');
        roleInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Rimuovi gli stili da tutte le label
                document.querySelectorAll('input[name="role"] + label').forEach(label => {
                    label.classList.remove('bg-red-50', 'border-red-500', 'ring-2', 'ring-red-500');
                    label.classList.add('bg-white', 'border-gray-200');
                });
                
                // Aggiungi gli stili alla label dell'input selezionato
                if (this.checked) {
                    const label = this.nextElementSibling;
                    label.classList.remove('bg-white', 'border-gray-200');
                    label.classList.add('bg-red-50', 'border-red-500', 'ring-2', 'ring-red-500');
                }
            });
        });
    });
</script>
@endsection 