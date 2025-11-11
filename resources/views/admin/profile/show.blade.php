@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">My Profile</h2>
                    <a href="{{ route('admin.profile.edit') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Profile
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Profile Image -->
                    <div class="flex flex-col items-center">
                        @if($admin->profile_image)
                            <img src="{{ asset('storage/' . $admin->profile_image) }}" 
                                 alt="Profile Image" 
                                 class="w-32 h-32 rounded-full object-cover mb-4">
                        @else
                            <div class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Profile Information -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $admin->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Surname</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $admin->surname }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $admin->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @foreach($admin->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Member Since</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $admin->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
