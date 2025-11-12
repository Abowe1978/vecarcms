@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800">Widgets</h1>
            <p class="text-gray-600 mt-1">Drag and drop widgets to customize the areas exposed by the active theme.</p>
        </div>

        @if($activeThemeObject)
            <div class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-2 rounded-lg flex items-center gap-3">
                <div class="flex flex-col">
                    <span class="text-sm uppercase tracking-wide font-semibold text-indigo-500">Active Theme</span>
                    <span class="font-semibold">{{ $activeThemeObject->display_name ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $activeTheme)) }}</span>
                    <span class="text-xs text-indigo-400">{{ $activeThemeObject->version ? 'v'.$activeThemeObject->version : '' }}</span>
                </div>
            </div>
        @endif
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
                    @forelse($zones as $zone)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">
                                    {{ $zone->display_name }}
                                    <span class="text-sm text-gray-500 font-normal ml-2">
                                        ({{ $zone->widgets->count() }} widgets)
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-400 mt-1 uppercase tracking-wide">Slug: {{ $zone->name }} {!! $zone->theme ? '<span class="mx-1">&bull;</span>' . e($zone->theme) : '' !!}</p>
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
                                                        <button type="button"
                                                                class="text-blue-600 hover:text-blue-800 p-2 js-widget-edit"
                                                                data-widget-id="{{ $widget->id }}"
                                                                title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button"
                                                                class="text-red-600 hover:text-red-800 p-2 js-widget-delete"
                                                                data-widget-id="{{ $widget->id }}"
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
                    @empty
                        <div class="border border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-500">
                                <i class="fas fa-layer-group text-3xl"></i>
                                <p class="text-sm">The active theme has not registered widget areas yet.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Widget Modal --}}
<div id="editWidgetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[85vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <h3 class="text-xl font-semibold">Edit Widget</h3>
                <p id="edit_widget_description" class="text-sm text-gray-500 mt-1"></p>
            </div>
            <button type="button" onclick="closeModal('editWidgetModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

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

            <div class="mb-6">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                    <input type="checkbox" id="edit_widget_is_active" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    Display this widget
                </label>
            </div>
            
            <div id="widget-config-fields" class="space-y-4 mb-4">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading widget settings…</span>
                </div>
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
const widgetFieldCache = {};
let currentWidgetBlueprint = null;
const hasValue = value => value !== undefined && value !== null;

function showToastError(message) {
    if (window.toast && typeof window.toast.error === 'function') {
        window.toast.error(message);
    } else {
        console.error(message);
    }
}

const widgetModal = {
    modal: document.getElementById('editWidgetModal'),
    form: document.getElementById('editWidgetForm'),
    idInput: document.getElementById('edit_widget_id'),
    titleInput: document.getElementById('edit_widget_title'),
    typeInput: document.getElementById('edit_widget_type'),
    description: document.getElementById('edit_widget_description'),
    fieldContainer: document.getElementById('widget-config-fields'),
    isActiveInput: document.getElementById('edit_widget_is_active'),
};

// Utilities
function showWidgetFieldMessage(message, icon = 'fas fa-info-circle') {
    widgetModal.fieldContainer.innerHTML = `
        <div class="flex items-center gap-2 text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded px-3 py-2">
            <i class="${icon}"></i>
            <span>${message}</span>
        </div>
    `;
}

function renderWidgetFields(fields, values = {}) {
    widgetModal.fieldContainer.innerHTML = '';

    if (!fields || fields.length === 0) {
        showWidgetFieldMessage('This widget does not expose additional settings.');
        return;
    }

    fields.forEach(field => {
        const wrapper = document.createElement('div');
        wrapper.className = 'space-y-2';

        const resolvedDefault = hasValue(field.default) ? field.default : (field.type === 'checkbox' ? false : '');
        const fieldValue = hasValue(values[field.name]) ? values[field.name] : resolvedDefault;
        let input;
        let label;

        switch (field.type) {
            case 'checkbox':
                label = document.createElement('label');
                label.className = 'inline-flex items-center gap-2 text-sm font-medium text-gray-700';

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.checked = Boolean(fieldValue);
                checkbox.className = 'rounded border-gray-300 text-purple-600 focus:ring-purple-500';
                checkbox.id = `widget-field-${field.name}`;
                checkbox.dataset.fieldName = field.name;
                checkbox.dataset.fieldType = field.type;

                label.appendChild(checkbox);

                const checkboxText = document.createElement('span');
                checkboxText.textContent = field.label || field.name;
                label.appendChild(checkboxText);

                wrapper.appendChild(label);
                break;

            case 'number':
                label = document.createElement('label');
                label.className = 'block text-sm font-medium text-gray-700';
                label.setAttribute('for', `widget-field-${field.name}`);
                label.textContent = field.label || field.name;
                wrapper.appendChild(label);

                input = document.createElement('input');
                input.type = 'number';
                if (field.min !== undefined) input.min = field.min;
                if (field.max !== undefined) input.max = field.max;
                if (field.step !== undefined) input.step = field.step;
                input.value = hasValue(fieldValue) ? fieldValue : '';
                break;

            case 'textarea':
                label = document.createElement('label');
                label.className = 'block text-sm font-medium text-gray-700';
                label.setAttribute('for', `widget-field-${field.name}`);
                label.textContent = field.label || field.name;
                wrapper.appendChild(label);

                input = document.createElement('textarea');
                input.rows = field.rows || 4;
                input.value = hasValue(fieldValue) ? fieldValue : '';
                break;

            case 'select':
                label = document.createElement('label');
                label.className = 'block text-sm font-medium text-gray-700';
                label.setAttribute('for', `widget-field-${field.name}`);
                label.textContent = field.label || field.name;
                wrapper.appendChild(label);

                input = document.createElement('select');
                input.multiple = Boolean(field.multiple);
                const options = field.options || [];
                options.forEach(option => {
                    const optionEl = document.createElement('option');
                    if (typeof option === 'object') {
                        const optionValue = hasValue(option.value)
                            ? option.value
                            : (hasValue(option.key) ? option.key : option);
                        optionEl.value = optionValue;
                        optionEl.textContent = hasValue(option.label)
                            ? option.label
                            : (hasValue(option.name) ? option.name : (hasValue(option.value) ? option.value : optionValue));
                    } else {
                        optionEl.value = option;
                        optionEl.textContent = option;
                    }

                    if (Array.isArray(fieldValue)) {
                        optionEl.selected = fieldValue.includes(optionEl.value);
                    } else {
                        optionEl.selected = optionEl.value == fieldValue;
                    }

                    input.appendChild(optionEl);
                });
                break;

            default:
                label = document.createElement('label');
                label.className = 'block text-sm font-medium text-gray-700';
                label.setAttribute('for', `widget-field-${field.name}`);
                label.textContent = field.label || field.name;
                wrapper.appendChild(label);

                input = document.createElement('input');
                input.type = field.type === 'email' ? 'email' : 'text';
                input.value = hasValue(fieldValue) ? fieldValue : '';
        }

        if (input) {
            input.id = `widget-field-${field.name}`;
            input.dataset.fieldName = field.name;
            input.dataset.fieldType = field.type;
            input.classList.add('w-full', 'rounded-md', 'border-gray-300', 'focus:ring-purple-500', 'focus:border-purple-500');
            if (field.placeholder) input.placeholder = field.placeholder;
            wrapper.appendChild(input);
        }

        if (field.help || field.description) {
            const help = document.createElement('p');
            help.className = 'text-xs text-gray-500';
            help.textContent = field.help || field.description;
            wrapper.appendChild(help);
        }

        widgetModal.fieldContainer.appendChild(wrapper);
    });
}

function collectWidgetSettings() {
    const settings = {};
    const inputs = widgetModal.fieldContainer.querySelectorAll('[data-field-name]');

    inputs.forEach(input => {
        const name = input.dataset.fieldName;
        const type = input.dataset.fieldType;
        let value = null;

        switch (type) {
            case 'checkbox':
                value = input.checked;
                break;
            case 'number':
                value = input.value === '' ? null : Number(input.value);
                break;
            case 'select':
                if (input.multiple) {
                    value = Array.from(input.selectedOptions).map(option => option.value);
                } else {
                    value = input.value;
                }
                break;
            default:
                value = input.value;
        }

        settings[name] = value;
    });

    return settings;
}

async function fetchWidgetBlueprint(type) {
    if (widgetFieldCache[type]) {
        return widgetFieldCache[type];
    }

    const response = await fetch(`/admin/widgets/types/${encodeURIComponent(type)}/fields`, {
        headers: { 'Accept': 'application/json' }
    });

    if (!response.ok) {
        throw new Error('Unable to load widget definition');
    }

    const data = await response.json();

    if (!data.success) {
        throw new Error(data.message || 'Widget definition unavailable');
    }

    widgetFieldCache[type] = data;
    return data;
}

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const availableWidgets = document.getElementById('available-widgets');
    if (availableWidgets) {
        new Sortable(availableWidgets, {
            group: { name: 'widgets', pull: 'clone', put: false },
            sort: false,
            animation: 150
        });
    }

    document.querySelectorAll('.widget-drop-zone').forEach(zone => {
        new Sortable(zone, {
            group: { name: 'widgets', put: true },
            animation: 150,
            handle: '.fa-grip-vertical',
            onAdd: handleWidgetAdd,
            onUpdate: handleWidgetReorder
        });
    });

    document.querySelectorAll('.js-widget-edit').forEach(button => {
        button.addEventListener('click', () => editWidget(button.dataset.widgetId));
    });

    document.querySelectorAll('.js-widget-delete').forEach(button => {
        button.addEventListener('click', () => deleteWidget(button.dataset.widgetId));
    });
});

// Handle widget add to zone
async function handleWidgetAdd(evt) {
    const widgetType = evt.item.dataset.widgetType;
    const zoneId = evt.to.dataset.zoneId;

    if (!widgetType || !zoneId) {
        evt.item.remove();
        return;
    }

    let defaultSettings = {};

    try {
        const blueprint = await fetchWidgetBlueprint(widgetType);

        (blueprint.fields || []).forEach(field => {
            const hasDefault = hasValue(field.default);
            if (hasDefault) {
                defaultSettings[field.name] = field.default;
            } else {
                defaultSettings[field.name] = field.type === 'checkbox' ? false : null;
            }
        });
    } catch (error) {
        console.warn('Unable to load widget defaults', error);
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
                settings: defaultSettings,
                is_active: true
            })
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            showToastError(data.message || 'Error adding widget');
            evt.item.remove();
        }
    } catch (error) {
        console.error('Error:', error);
        showToastError('Error adding widget');
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
            showToastError(data.message || 'Error deleting widget');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting widget');
    }
}

// Edit widget
async function editWidget(widgetId) {
    widgetModal.modal.classList.remove('hidden');
    widgetModal.modal.classList.add('flex');
    widgetModal.fieldContainer.innerHTML = `
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Loading widget settings…</span>
        </div>
    `;

    try {
        const response = await fetch(`/admin/widgets/${widgetId}/edit`, {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            throw new Error('Unable to load widget');
        }

        const data = await response.json();
        const widget = data.widget;

        widgetModal.idInput.value = widget.id;
        widgetModal.titleInput.value = hasValue(widget.title) ? widget.title : '';
        widgetModal.typeInput.value = widget.type;
        widgetModal.isActiveInput.checked = Boolean(widget.is_active);

        try {
            const blueprint = await fetchWidgetBlueprint(widget.type);
            currentWidgetBlueprint = blueprint;
            widgetModal.description.textContent = blueprint.description || '';
            renderWidgetFields(blueprint.fields || [], widget.settings || {});
        } catch (definitionError) {
            currentWidgetBlueprint = null;
            widgetModal.description.textContent = '';
            console.warn(definitionError);
            showWidgetFieldMessage('This widget cannot be configured because its definition is missing.', 'fas fa-exclamation-triangle');
        }
    } catch (error) {
        console.error('Error:', error);
        showToastError('Error loading widget data');
        closeModal('editWidgetModal');
    }
}

// Update widget
widgetModal.form.addEventListener('submit', async function(e) {
    e.preventDefault();

    const widgetId = widgetModal.idInput.value;
    const title = widgetModal.titleInput.value;
    const isActive = widgetModal.isActiveInput.checked;
    const settings = collectWidgetSettings();

    try {
        const response = await fetch(`/admin/widgets/${widgetId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title,
                settings,
                is_active: isActive
            })
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            showToastError(data.message || 'Error updating widget');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating widget');
    }
});

// Close modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush
@endsection
