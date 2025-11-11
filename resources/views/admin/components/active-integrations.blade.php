@php
    use App\Models\Integration;
    $activeIntegrations = Integration::where('is_enabled', true)->get();
@endphp

@foreach($activeIntegrations as $integration)
    <li>
        <a href="{{ route('admin.integrations.show', $integration->module_name) }}" 
           class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-800 {{ request()->is('admin/integrations/' . $integration->module_name) ? 'bg-gray-700' : '' }}">
            <i class="fas fa-plug"></i>
            <span class="ml-2 transition-opacity duration-300"
                  :class="{'opacity-0': !sidebarOpen || isMobile, 'hidden': !sidebarOpen && !isMobile}">
                {{ $integration->name }}
            </span>
        </a>
    </li>
@endforeach 