<div class="border border-gray-200 rounded p-3 mb-2" style="margin-left: {{ $level * 20 }}px;">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <i class="fas fa-grip-vertical text-gray-400 cursor-move"></i>
            <div>
                <div class="font-medium">{{ $item->title }}</div>
                <div class="text-sm text-gray-500">
                    {{ $item->url }}
                    @if(!$item->is_active)
                        <span class="ml-2 px-2 py-1 text-xs bg-gray-100 rounded">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="editItem({{ $item->id }})" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-edit"></i>
            </button>
            <button onclick="deleteItem({{ $item->id }})" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>

@if($item->children->count() > 0)
    @foreach($item->children->sortBy('order') as $child)
        @include('admin.menus.partials.menu-item', ['item' => $child, 'level' => $level + 1])
    @endforeach
@endif

