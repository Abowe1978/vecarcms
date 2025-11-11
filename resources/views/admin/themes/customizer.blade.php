@extends('layouts.admin')

@section('content')
<div class="h-screen flex flex-col">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.themes.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Customize: {{ $theme->name }}</h1>
                <p class="text-sm text-gray-600">Preview your changes in real-time</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="resetSettings()" class="px-4 py-2 text-gray-700 hover:text-gray-900 transition">
                <i class="fas fa-undo mr-2"></i>Reset
            </button>
            <button onclick="saveSettings()" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Publish
            </button>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="flex-1 flex overflow-hidden">
        {{-- Customizer Panel --}}
        <div class="w-80 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="p-6 space-y-6" id="customizer-panels">
                {{-- Colors Section --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-palette text-purple-600 mr-2"></i>
                        Colors
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" 
                                       id="color_primary" 
                                       value="{{ $settings['colors']['primary'] ?? '#667eea' }}"
                                       class="w-12 h-10 rounded border border-gray-300 cursor-pointer"
                                       onchange="updateSetting('colors.primary', this.value)">
                                <input type="text" 
                                       value="{{ $settings['colors']['primary'] ?? '#667eea' }}"
                                       class="flex-1 rounded-md border-gray-300 text-sm"
                                       readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" 
                                       id="color_secondary" 
                                       value="{{ $settings['colors']['secondary'] ?? '#764ba2' }}"
                                       class="w-12 h-10 rounded border border-gray-300 cursor-pointer"
                                       onchange="updateSetting('colors.secondary', this.value)">
                                <input type="text" 
                                       value="{{ $settings['colors']['secondary'] ?? '#764ba2' }}"
                                       class="flex-1 rounded-md border-gray-300 text-sm"
                                       readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" 
                                       id="color_accent" 
                                       value="{{ $settings['colors']['accent'] ?? '#f59e0b' }}"
                                       class="w-12 h-10 rounded border border-gray-300 cursor-pointer"
                                       onchange="updateSetting('colors.accent', this.value)">
                                <input type="text" 
                                       value="{{ $settings['colors']['accent'] ?? '#f59e0b' }}"
                                       class="flex-1 rounded-md border-gray-300 text-sm"
                                       readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Typography Section --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-font text-purple-600 mr-2"></i>
                        Typography
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heading Font</label>
                            <select id="font_heading" 
                                    class="w-full rounded-md border-gray-300"
                                    onchange="updateSetting('typography.heading_font', this.value)">
                                <option value="Inter" {{ ($settings['typography']['heading_font'] ?? 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                                <option value="Poppins" {{ ($settings['typography']['heading_font'] ?? '') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                <option value="Montserrat" {{ ($settings['typography']['heading_font'] ?? '') === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                <option value="Roboto" {{ ($settings['typography']['heading_font'] ?? '') === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Body Font</label>
                            <select id="font_body" 
                                    class="w-full rounded-md border-gray-300"
                                    onchange="updateSetting('typography.body_font', this.value)">
                                <option value="Inter" {{ ($settings['typography']['body_font'] ?? 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                                <option value="Poppins" {{ ($settings['typography']['body_font'] ?? '') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                <option value="Open Sans" {{ ($settings['typography']['body_font'] ?? '') === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Lato" {{ ($settings['typography']['body_font'] ?? '') === 'Lato' ? 'selected' : '' }}>Lato</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Base Font Size</label>
                            <div class="flex items-center gap-2">
                                <input type="range" 
                                       id="font_size" 
                                       min="14" 
                                       max="20" 
                                       value="{{ $settings['typography']['base_size'] ?? 16 }}"
                                       class="flex-1"
                                       oninput="updateFontSize(this.value)">
                                <span id="font_size_display" class="text-sm font-medium w-12">{{ $settings['typography']['base_size'] ?? 16 }}px</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Layout Section --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-th-large text-purple-600 mr-2"></i>
                        Layout
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Container Width</label>
                            <select id="container_width" 
                                    class="w-full rounded-md border-gray-300"
                                    onchange="updateSetting('layout.container_width', this.value)">
                                <option value="1140px" {{ ($settings['layout']['container_width'] ?? '1140px') === '1140px' ? 'selected' : '' }}>Default (1140px)</option>
                                <option value="1320px" {{ ($settings['layout']['container_width'] ?? '') === '1320px' ? 'selected' : '' }}>Wide (1320px)</option>
                                <option value="960px" {{ ($settings['layout']['container_width'] ?? '') === '960px' ? 'selected' : '' }}>Narrow (960px)</option>
                                <option value="100%" {{ ($settings['layout']['container_width'] ?? '') === '100%' ? 'selected' : '' }}>Full Width</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Border Radius</label>
                            <div class="flex items-center gap-2">
                                <input type="range" 
                                       id="border_radius" 
                                       min="0" 
                                       max="24" 
                                       value="{{ $settings['layout']['border_radius'] ?? 8 }}"
                                       class="flex-1"
                                       oninput="updateBorderRadius(this.value)">
                                <span id="border_radius_display" class="text-sm font-medium w-12">{{ $settings['layout']['border_radius'] ?? 8 }}px</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Custom CSS --}}
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-code text-purple-600 mr-2"></i>
                        Custom CSS
                    </h3>
                    
                    <textarea id="custom_css" 
                              rows="6"
                              class="w-full rounded-md border-gray-300 font-mono text-sm"
                              placeholder="/* Add your custom CSS here */"
                              onchange="updateSetting('custom_css', this.value)">{{ $settings['custom_css'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="flex-1 bg-gray-100 overflow-hidden">
            <div class="h-full flex flex-col">
                {{-- Device Selector --}}
                <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-center gap-2">
                    <button onclick="setDevice('desktop')" 
                            class="device-btn active px-4 py-2 rounded transition"
                            data-device="desktop">
                        <i class="fas fa-desktop mr-2"></i>Desktop
                    </button>
                    <button onclick="setDevice('tablet')" 
                            class="device-btn px-4 py-2 rounded transition"
                            data-device="tablet">
                        <i class="fas fa-tablet-alt mr-2"></i>Tablet
                    </button>
                    <button onclick="setDevice('mobile')" 
                            class="device-btn px-4 py-2 rounded transition"
                            data-device="mobile">
                        <i class="fas fa-mobile-alt mr-2"></i>Mobile
                    </button>
                </div>

                {{-- Preview Frame --}}
                <div class="flex-1 flex items-center justify-center p-8">
                    <div id="preview-wrapper" class="preview-desktop bg-white shadow-2xl rounded-lg overflow-hidden transition-all duration-300">
                        <iframe id="preview-frame" 
                                src="{{ url('/') }}" 
                                class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.preview-desktop {
    width: 100%;
    height: 100%;
}

.preview-tablet {
    width: 768px;
    height: 1024px;
}

.preview-mobile {
    width: 375px;
    height: 667px;
}

.device-btn {
    background: transparent;
    color: #6b7280;
}

.device-btn.active {
    background: #7c3aed;
    color: white;
}

.device-btn:hover:not(.active) {
    background: #f3f4f6;
}
</style>

@push('scripts')
<script>
const themeId = {{ $theme->id }};
const csrfToken = '{{ csrf_token() }}';
let currentSettings = @json($settings);

// Update setting
function updateSetting(key, value) {
    // Update settings object
    const keys = key.split('.');
    let obj = currentSettings;
    
    for (let i = 0; i < keys.length - 1; i++) {
        if (!obj[keys[i]]) {
            obj[keys[i]] = {};
        }
        obj = obj[keys[i]];
    }
    
    obj[keys[keys.length - 1]] = value;
    
    // Update preview
    updatePreview();
}

// Update font size display
function updateFontSize(value) {
    document.getElementById('font_size_display').textContent = value + 'px';
    updateSetting('typography.base_size', value);
}

// Update border radius display
function updateBorderRadius(value) {
    document.getElementById('border_radius_display').textContent = value + 'px';
    updateSetting('layout.border_radius', value);
}

// Update preview iframe with custom CSS
function updatePreview() {
    const iframe = document.getElementById('preview-frame');
    
    if (!iframe || !iframe.contentWindow) {
        return;
    }

    try {
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        
        // Remove existing custom style
        const existingStyle = iframeDoc.getElementById('theme-customizer-preview');
        if (existingStyle) {
            existingStyle.remove();
        }

        // Create new style element
        const style = iframeDoc.createElement('style');
        style.id = 'theme-customizer-preview';
        
        let css = ':root {';
        if (currentSettings.colors) {
            if (currentSettings.colors.primary) css += `--color-primary: ${currentSettings.colors.primary};`;
            if (currentSettings.colors.secondary) css += `--color-secondary: ${currentSettings.colors.secondary};`;
            if (currentSettings.colors.accent) css += `--color-accent: ${currentSettings.colors.accent};`;
        }
        css += '}';

        if (currentSettings.typography) {
            if (currentSettings.typography.heading_font) {
                css += `h1, h2, h3, h4, h5, h6 { font-family: "${currentSettings.typography.heading_font}", sans-serif; }`;
            }
            if (currentSettings.typography.body_font) {
                css += `body { font-family: "${currentSettings.typography.body_font}", sans-serif; }`;
            }
            if (currentSettings.typography.base_size) {
                css += `body { font-size: ${currentSettings.typography.base_size}px; }`;
            }
        }

        if (currentSettings.layout) {
            if (currentSettings.layout.container_width) {
                css += `.container { max-width: ${currentSettings.layout.container_width}; }`;
            }
            if (currentSettings.layout.border_radius) {
                css += `* { --tw-rounded: ${currentSettings.layout.border_radius}px; }`;
            }
        }

        if (currentSettings.custom_css) {
            css += currentSettings.custom_css;
        }

        style.textContent = css;
        iframeDoc.head.appendChild(style);
    } catch (e) {
        console.error('Error updating preview:', e);
    }
}

// Save settings
async function saveSettings() {
    try {
        const response = await fetch(`/admin/themes/${themeId}/customizer`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                settings: currentSettings
            })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Theme settings saved successfully!');
        } else {
            alert(data.message || 'Error saving settings');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving settings');
    }
}

// Reset settings
function resetSettings() {
    if (confirm('Reset all theme settings to defaults?')) {
        window.location.href = `/admin/themes/${themeId}/customizer/reset`;
    }
}

// Set device preview
function setDevice(device) {
    const wrapper = document.getElementById('preview-wrapper');
    const buttons = document.querySelectorAll('.device-btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[data-device="${device}"]`).classList.add('active');
    
    wrapper.className = `preview-${device} bg-white shadow-2xl rounded-lg overflow-hidden transition-all duration-300`;
}

// Initialize preview on iframe load
document.getElementById('preview-frame').addEventListener('load', function() {
    updatePreview();
});
</script>
@endpush
@endsection

