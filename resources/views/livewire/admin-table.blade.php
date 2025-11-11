<div>
    <!-- Tabella degli amministratori -->
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.admins.full_name') }}
                        <span class="inline-flex space-x-1">
                            <button wire:click="sortBy('name')" class="text-xs text-gray-400 focus:outline-none">
                                @if($sortField === 'name')
                                    @if($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                                <span class="sr-only">{{ __('admin.admins.name') }}</span>
                            </button>
                            <button wire:click="sortBy('last_name')" class="text-xs text-gray-400 focus:outline-none">
                                @if($sortField === 'last_name')
                                    @if($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                                <span class="sr-only">{{ __('admin.admins.last_name') }}</span>
                            </button>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.admins.email') }}
                        <button wire:click="sortBy('email')" class="ml-1 text-xs text-gray-400 focus:outline-none">
                            @if($sortField === 'email')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.admins.role') }}
                        <button wire:click="sortBy('role')" class="ml-1 text-xs text-gray-400 focus:outline-none">
                            @if($sortField === 'role')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.admins.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($admins as $admin)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($admin->profile_image)
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200" 
                                             src="{{ asset('storage/' . $admin->profile_image) }}" 
                                             alt="{{ $admin->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full flex items-center justify-center bg-blue-100 text-blue-500 border border-gray-200">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $admin->name }} {{ $admin->last_name }}
                                    </div>
                                    @if ($admin->hasRole('developer'))
                                        <span class="inline-flex mt-0.5 items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="h-2 w-2 text-purple-400 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            {{ __('Developer') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $admin->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($admin->roles as $role)
                                @if($role->name === 'super-admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @elseif($role->name === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @elseif($role->name === 'moderator')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @elseif($role->name === 'member')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @elseif($role->name === 'developer')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.admins.edit', $admin) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" 
                                   title="{{ __('admin.admins.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if(!$admin->hasRole('developer') && $admin->id !== auth()->id())
                                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900" 
                                            title="{{ __('admin.admins.delete') }}"
                                            onclick="return confirm('{{ __('admin.admins.confirm_delete') }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            {{ __('admin.admins.no_admins_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginazione -->
    <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6 rounded-b-lg shadow">
        {{ $admins->links() }}
    </div>
</div>
