<div>
    <div class="overflow-x-auto">
        <div class="mb-4">
            <div class="max-w-md">
                <div class="relative flex items-center w-full">
                    <input type="text" wire:model.debounce.300ms="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="{{ __('admin.pages.search_placeholder') }}">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    @if ($search)
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button wire:click="$set('search', '')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('title')">
                        <div class="flex items-center">
                            {{ __('admin.pages.table.title') }}
                            @if ($sortField === 'title')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1"></i>
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('slug')">
                        <div class="flex items-center">
                            {{ __('admin.pages.table.slug') }}
                            @if ($sortField === 'slug')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1"></i>
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('template')">
                        <div class="flex items-center">
                            {{ __('admin.pages.table.template') }}
                            @if ($sortField === 'template')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1"></i>
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('admin.pages.table.parent') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('is_published')">
                        <div class="flex items-center">
                            {{ __('admin.pages.table.status') }}
                            @if ($sortField === 'is_published')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1"></i>
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('order')">
                        <div class="flex items-center">
                            {{ __('admin.pages.table.order') }}
                            @if ($sortField === 'order')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1"></i>
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">{{ __('admin.pages.table.actions') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="pagesTable">
                @forelse ($pages as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                        {{ $page->title }}
                                        @if($page->is_homepage)
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 inline-flex items-center">
                                                <i class="ri-home-4-line me-1"></i>Homepage
                                            </span>
                                        @endif
                                        @if($page->is_blog)
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 inline-flex items-center">
                                                <i class="ri-article-line me-1"></i>Blog Page
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ __('admin.pages.created_at') }}: {{ $page->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $page->slug }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $page->template }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $page->parent ? $page->parent->title : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $page->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $page->is_published ? __('admin.pages.published') : __('admin.pages.draft') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $page->order }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.pages.show', $page) }}" class="text-indigo-600 hover:text-indigo-900" title="{{ __('admin.pages.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:text-blue-900" title="{{ __('admin.pages.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline-block" onsubmit="return confirm('Sei sicuro di voler eliminare questa pagina?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="{{ __('admin.pages.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ __('admin.pages.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pages->links() }}
    </div>
</div>
