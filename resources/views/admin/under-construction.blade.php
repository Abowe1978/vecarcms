@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col items-center justify-center py-12">
                    <i class="fas fa-hard-hat text-6xl text-amber-500 mb-4"></i>
                    <h1 class="text-3xl font-bold mb-4">{{ __('admin.under_construction.title') }}</h1>
                    <p class="text-xl mb-8">{{ __('admin.under_construction.message', ['module' => $module]) }}</p>
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('admin.back_to_dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 