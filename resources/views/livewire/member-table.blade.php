<div>
    <!-- Simple Search Bar -->
    <div class="mb-4 bg-white shadow rounded-lg p-4">
        <h3 class="text-lg font-medium mb-4">Search and Filter</h3>
        
        <!-- Search Input -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Search Members
            </label>
            <input 
                type="text" 
                wire:model.live.debounce.500ms="search"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search by name or email..."
            >
        </div>
        
        <!-- Per Page Selector -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Members per page
            </label>
            <select wire:model.live.debounce.500ms="perPage" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <!-- Tabella dei membri -->
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.members.full_name') }}
                        <span class="inline-flex space-x-1">
                            <button wire:click="sortBy('name')" class="text-xs text-gray-400 focus:outline-none">
                                <i class="fas fa-sort{{ $sortField === 'name' ? '-' . $sortDirection : '' }}"></i>
                            </button>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.members.email') }}
                        <span class="inline-flex space-x-1">
                            <button wire:click="sortBy('email')" class="text-xs text-gray-400 focus:outline-none">
                                <i class="fas fa-sort{{ $sortField === 'email' ? '-' . $sortDirection : '' }}"></i>
                            </button>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.members.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($members as $member)
                <tr class="{{ $member->trashed() ? 'bg-red-50 opacity-75' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if ($member->profile_image)
                                    <img class="h-10 w-10 rounded-full object-cover {{ $member->trashed() ? 'grayscale' : '' }}" src="{{ asset($member->profile_image) }}" alt="{{ $member->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full {{ $member->trashed() ? 'bg-red-200' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas fa-user {{ $member->trashed() ? 'text-red-400' : 'text-gray-400' }}"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium {{ $member->trashed() ? 'text-gray-500 line-through' : 'text-gray-900' }}">
                                    {{ $member->name }} {{ $member->surname }}
                                </div>
                                @if($member->trashed())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-trash-alt mr-1"></i> Deleted {{ $member->deleted_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm {{ $member->trashed() ? 'text-gray-500' : 'text-gray-900' }}">{{ $member->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            @if(!$member->trashed())
                                <button wire:click="viewMember({{ $member->id }})" class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.members.edit', $member->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button wire:click="viewMember({{ $member->id }})" class="text-gray-400 hover:text-gray-600" title="View (Read-only)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('admin.members.restore', $member->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to restore this member?')">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Restore">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                                <form action="{{ route('admin.members.force-delete', $member->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to permanently delete this member? This action cannot be undone!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-800 hover:text-red-900" title="Permanently Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-500">{{ __('admin.members.no_members_found') }}</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} results
        </div>
        
        <div class="flex items-center space-x-2">
            <!-- Previous Button -->
            @if ($members->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                    Previous
                </span>
            @else
                <button wire:click="previousPage" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Previous
                </button>
            @endif

            <!-- Page Numbers -->
            <div class="flex space-x-1">
                @php
                    $currentPage = $members->currentPage();
                    $lastPage = $members->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp
                
                @if ($start > 1)
                    <button wire:click="setPage(1)" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">1</button>
                    @if ($start > 2)
                        <span class="px-3 py-2 text-sm text-gray-500">...</span>
                    @endif
                @endif
                
                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $currentPage)
                        <span class="px-3 py-2 text-sm text-white bg-blue-600 rounded-md">{{ $page }}</span>
                    @else
                        <button wire:click="setPage({{ $page }})" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {{ $page }}
                        </button>
                    @endif
                @endfor
                
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-3 py-2 text-sm text-gray-500">...</span>
                    @endif
                    <button wire:click="setPage({{ $lastPage }})" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $lastPage }}</button>
                @endif
            </div>

            <!-- Next Button -->
            @if ($members->hasMorePages())
                <button wire:click="nextPage" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Next
                </button>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
    </div>
</div> 