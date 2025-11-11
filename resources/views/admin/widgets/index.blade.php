@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800">Widgets</h1>
            <p class="text-gray-600 mt-1">Drag and drop widgets to customize your sidebar and footer areas</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Available Widgets --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-puzzle-piece text-purple-600 mr-2"></i>
                    Available Widgets
                </h2>

                <div class="space-y-3" id="available-widgets">
                    @foreach($availableWidgets as $type => $widget)
                        <div class="widget-item border border-gray-200 rounded-lg p-4 cursor-move hover:border-purple-500 hover:shadow-md transition"
                             data-widget-type="{{ $type }}"
                             draggable="true">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="{{ $widget['icon'] }} text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $widget['name'] }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $widget['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Widget Zones --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-th-large text-purple-600 mr-2"></i>
                    Widget Zones
                </h2>

                <div class="space-y-4">
                    @foreach($zones as $zone)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">
                                    {{ $zone->name }}
                                    <span class="text-sm text-gray-500 font-normal ml-2">
                                        ({{ $zone->widgets->count() }} widgets)
                                    </span>
                                </h3>
                                @if($zone->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $zone->description }}</p>
                                @endif
                            </div>
                            
                            <div class="p-4 min-h-[100px] bg-white widget-drop-zone" 
                                 data-zone-id="{{ $zone->id }}"
                                 data-zone-name="{{ $zone->name }}">
                                @if($zone->widgets->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($zone->widgets->sortBy('order') as $widget)
                                            <div class="widget-assigned border border-gray-200 rounded-lg p-4 bg-gray-50 hover:border-purple-500 transition cursor-move"
                                                 data-widget-id="{{ $widget->id }}"
                                                 draggable="true">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <i class="fas fa-grip-vertical text-gray-400"></i>
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900">{{ $widget->title }}</h4>
                                                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('-', ' ', $widget->type)) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button onclick="editWidget({{ $widget->id }})" 
                                                                class="text-blue-600 hover:text-blue-800 p-2"
                                                                title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button onclick="deleteWidget({{ $widget->id }})" 
                                                                class="text-red-600 hover:text-red-800 p-2"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-gray-400 py-8">
                                        <i class="fas fa-inbox text-3xl mb-2"></i>
                                        <p class="text-sm">Drop widgets here</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Widget Modal --}}
<div id="editWidgetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 max-h-[80vh] overflow-y-auto">
        <h3 class="text-xl font-semibold mb-4">Edit Widget</h3>
        <form id="editWidgetForm">
            <input type="hidden" id="edit_widget_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Widget Title</label>
                <input type="text" id="edit_widget_title" class="w-full rounded-md border-gray-300" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Widget Type</label>
                <input type="text" id="edit_widget_type" class="w-full rounded-md border-gray-300 bg-gray-100" readonly>
            </div>
            
            <div id="widget-config-fields" class="space-y-4 mb-4">
                {{-- Dynamic fields based on widget type --}}
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editWidgetModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
const csrfToken = '{{ csrf_token() }}';

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    // Sortable for available widgets (clone)
    const availableWidgets = document.getElementById('available-widgets');
    if (availableWidgets) {
        new Sortable(availableWidgets, {
            group: {
                name: 'widgets',
                pull: 'clone',
                put: false
            },
            sort: false,
            animation: 150
        });
    }

    // Sortable for each widget zone
    document.querySelectorAll('.widget-drop-zone').forEach(zone => {
        new Sortable(zone, {
            group: {
                name: 'widgets',
                put: true
            },
            animation: 150,
            handle: '.fa-grip-vertical',
            onAdd: function(evt) {
                handleWidgetAdd(evt);
            },
            onUpdate: function(evt) {
                handleWidgetReorder(evt);
            }
        });
    });
});

// Handle widget add to zone
async function handleWidgetAdd(evt) {
    const widgetType = evt.item.dataset.widgetType;
    const zoneId = evt.to.dataset.zoneId;
    const zoneName = evt.to.dataset.zoneName;
    
    if (!widgetType || !zoneId) {
        evt.item.remove();
        return;
    }

    try {
        const response = await fetch('/admin/widgets', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                zone_id: zoneId,
                type: widgetType,
                title: evt.item.querySelector('h3').textContent.trim(),
                config: {},
                is_active: true
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Reload page to show the new widget properly
            location.reload();
        } else {
            window.toast.error(data.message || 'Error adding widget');
            evt.item.remove();
        }
    } catch (error) {
        console.error('Error:', error);
        window.toast.error('Error adding widget');
        evt.item.remove();
    }
}

// Handle widget reorder
async function handleWidgetReorder(evt) {
    const zoneId = evt.to.dataset.zoneId;
    const widgets = Array.from(evt.to.querySelectorAll('.widget-assigned')).map(el => el.dataset.widgetId);
    
    try {
        await fetch('/admin/widgets/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                zone_id: zoneId,
                widget_ids: widgets
            })
        });
    } catch (error) {
        console.error('Error reordering widgets:', error);
    }
}

// Delete widget
async function deleteWidget(widgetId) {
    if (!confirm('Are you sure you want to delete this widget?')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/widgets/${widgetId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            window.toast.error(data.message || 'Error deleting widget');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting widget');
    }
}

// Edit widget
function editWidget(widgetId) {
    // Fetch widget data and show modal
    fetch(`/admin/widgets/${widgetId}/edit`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('edit_widget_id').value = data.widget.id;
        document.getElementById('edit_widget_title').value = data.widget.title;
        document.getElementById('edit_widget_type').value = data.widget.type;
        
        // Show modal
        document.getElementById('editWidgetModal').classList.remove('hidden');
        document.getElementById('editWidgetModal').classList.add('flex');
    })
    .catch(error => {
        console.error('Error:', error);
        window.toast.error('Error loading widget data');
    });
}

// Update widget
document.getElementById('editWidgetForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const widgetId = document.getElementById('edit_widget_id').value;
    const title = document.getElementById('edit_widget_title').value;
    
    try {
        const response = await fetch(`/admin/widgets/${widgetId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title: title
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            window.toast.error(data.message || 'Error updating widget');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating widget');
    }
});

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}
</script>
@endpush
@endsection
