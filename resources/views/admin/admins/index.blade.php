@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Title, Search and Action Button -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('admin.admins.title') }}</h1>
        
        <!-- Search input moved to top bar -->
        <div class="mt-4 sm:mt-0 flex-grow mx-4 max-w-md">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" id="admin-search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('Search administrators...') }}">
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.admins.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus -ml-1 mr-2"></i>
                {{ __('admin.admins.create') }}
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Admin List Table -->
    <livewire:admin-table />
</div>

@push('scripts')
<!-- JavaScript to connect search field with Livewire component -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('admin-search');
        let debounceTimer;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    Livewire.dispatch('searchAdmins', { searchTerm: searchInput.value });
                }, 300);
            });
            
            // Initialize search input with URL param if present
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam) {
                searchInput.value = searchParam;
            }
            
            // Update search field when Livewire updates
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('searchUpdated', (searchTerm) => {
                    searchInput.value = searchTerm;
                });
            });
        }
    });
</script>
@endpush
@endsection 