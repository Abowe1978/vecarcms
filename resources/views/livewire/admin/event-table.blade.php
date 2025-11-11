@php
use Illuminate\Support\Str;
@endphp

<div>
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="{{ __('Search events...') }}" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="w-full lg:w-48">
                    <select wire:model.live="categoryFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-full lg:w-48">
                    <select wire:model.live="statusFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">{{ __('All Status') }}</option>
                        <option value="draft">{{ __('Draft') }}</option>
                        <option value="published">{{ __('Published') }}</option>
                        <option value="cancelled">{{ __('Cancelled') }}</option>
                        <option value="completed">{{ __('Completed') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('name')">
                            {{ __('Name') }}
                            @if($sortField === 'name')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Category') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('start_date')">
                            {{ __('Start Date') }}
                            @if($sortField === 'start_date')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Location') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('status')">
                            {{ __('Status') }}
                            @if($sortField === 'status')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Settings') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($event->primary_image_url)
                                        <img class="h-10 w-10 rounded-lg object-cover mr-3" 
                                             src="{{ $event->primary_image_url }}" 
                                             alt="{{ $event->name }}">
                                    @else
                                        <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                        <div class="text-sm text-gray-500">{!! Str::limit($event->description, 50) !!}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($event->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $event->category->name }}
                                    </span>
                                @endif
                                @if($event->subcategory)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                                        {{ $event->subcategory->name }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $event->start_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $event->start_time ? $event->start_time->format('H:i') : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $event->location_name ?: '--' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($event->status === 'published') bg-green-100 text-green-800
                                    @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800
                                    @elseif($event->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    @if($event->is_featured)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                    @endif
                                    @if($event->show_in_app)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-mobile-alt mr-1"></i> App
                                        </span>
                                    @endif
                                    @if($event->is_member_only)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-lock mr-1"></i> Members
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.events.show', $event) }}" 
                                       class="text-blue-600 hover:text-blue-900 px-2 py-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 px-2 py-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="duplicateEvent({{ $event->id }})" 
                                            class="text-green-600 hover:text-green-900 px-2 py-1">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button wire:click="deleteEvent({{ $event->id }})" 
                                            wire:confirm="{{ __('Are you sure you want to delete this event?') }}"
                                            class="text-red-600 hover:text-red-900 px-2 py-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar text-gray-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No events found') }}</h3>
                                    <p class="text-gray-500 mb-4">{{ __('Start by creating your first event.') }}</p>
                                    <a href="{{ route('admin.events.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i>
                                        {{ __('Create Event') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
