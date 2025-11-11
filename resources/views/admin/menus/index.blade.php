@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Menus</h1>
        <a href="{{ route('admin.menus.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Create Menu
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($menus as $menu)
                    <tr>
                        <td class="px-6 py-4">{{ $menu->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                {{ $menu->location ?: 'No location' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $menu->items->count() }} items</td>
                        <td class="px-6 py-4">
                            @if($menu->is_active)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.menus.edit', $menu) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this menu?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No menus found. Create your first menu!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

