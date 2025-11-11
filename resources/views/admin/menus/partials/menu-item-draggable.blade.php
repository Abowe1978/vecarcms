<div class="border border-gray-200 rounded p-4 bg-white hover:shadow-md transition-shadow" data-item-id="{{ $item->id }}">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3 flex-1">
            <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                <i class="fas fa-grip-vertical"></i>
            </div>
            <div class="flex-1">
                <div class="font-medium item-title">{{ $item->title }}</div>
                <div class="text-sm text-gray-500 flex items-center gap-2 item-url">
                    <span>{{ $item->url ?? $item->computed_url }}</span>
                    @if(!$item->is_active)
                        <span class="px-2 py-0.5 text-xs bg-gray-100 rounded">Inactive</span>
                    @endif
                    <span class="px-2 py-0.5 text-xs bg-blue-50 text-blue-600 rounded">{{ ucfirst($item->type) }}</span>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="editItem({{ $item->id }})" class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button onclick="deleteItem({{ $item->id }})" class="text-red-600 hover:text-red-800 p-2" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>

