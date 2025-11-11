@php use Illuminate\Support\Str; @endphp
<div>
    <div class="flex justify-between items-center mb-4">
        <div class="flex-1 pr-4">
            <div class="relative">
                <input
                    type="search"
                    wire:model.live.debounce.500ms="search"
                    class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                    placeholder="{{ __('admin.sections.search') }}"
                >
                <div class="absolute top-0 left-0 inline-flex items-center p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                        <circle cx="10" cy="10" r="7" />
                        <line x1="21" y1="21" x2="15" y2="15" />
                    </svg>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.sections.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('admin.sections.create') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
            <tr class="text-left font-bold">
                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50">
                    <button wire:click="sortBy('name')" class="flex items-center space-x-1">
                        <span>{{ __('admin.sections.name') }}</span>
                        @if($sortField === 'name')
                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        @endif
                    </button>
                </th>
                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50">{{ __('admin.sections.city') }}</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50">{{ __('admin.sections.country') }}</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50">
                    <button wire:click="sortBy('created_at')" class="flex items-center space-x-1">
                        <span>{{ __('admin.common.created_at') }}</span>
                        @if($sortField === 'created_at')
                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        @endif
                    </button>
                </th>
                <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50">{{ __('admin.sections.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($sections as $section)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $section->name }}
                                </div>
                                @if($section->description)
                                    <div class="text-sm text-gray-500">
                                        {!! Str::limit($section->description, 100) !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200">
                        {{ $section->city }}
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200">
                        {{ $section->country }}
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200">
                        {{ $section->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.sections.show', $section) }}"
                               class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.sections.edit', $section) }}"
                               class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button wire:click="deleteSection({{ $section->id }})"
                                    wire:confirm="{{ __('admin.sections.confirm_delete') }}"
                                    class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 border-b border-gray-200">
                        {{ __('admin.sections.no_sections_found') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sections->links() }}
    </div>
</div> 