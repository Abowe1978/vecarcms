@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Themes</h1>
        <div class="flex gap-3">
            <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-upload mr-2"></i>Upload Theme
            </button>
            <button onclick="scanThemes()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-sync mr-2"></i>Scan Themes
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($themes as $theme)
            <div class="bg-white rounded-lg shadow-md overflow-hidden {{ $theme->is_active ? 'ring-2 ring-purple-500' : '' }}">
                <div class="h-48 bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center">
                    @if($theme->screenshot)
                        <img src="{{ asset($theme->screenshot) }}" alt="{{ $theme->display_name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-paint-brush text-6xl text-gray-300"></i>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2">
                        {{ $theme->display_name }}
                        @if($theme->is_active)
                            <span class="text-xs bg-purple-500 text-white px-2 py-1 rounded ml-2">Active</span>
                        @endif
                    </h3>
                    
                    @if($theme->description)
                        <p class="text-gray-600 text-sm mb-3">{{ $theme->description }}</p>
                    @endif
                    
                    <div class="text-xs text-gray-500 mb-4">
                        <div>Version: {{ $theme->version }}</div>
                        @if($theme->author)
                            <div>By: {{ $theme->author }}</div>
                        @endif
                    </div>
                    
                    <div class="flex gap-2">
                        @if($theme->is_active)
                            <span class="flex-1 bg-green-100 text-green-700 px-4 py-2 rounded text-center font-medium">
                                <i class="fas fa-check mr-2"></i>Active
                            </span>
                            <a href="{{ route('admin.themes.customizer', $theme) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                                <i class="fas fa-paint-brush"></i>
                            </a>
                        @else
                            <form action="{{ route('admin.themes.activate', $theme) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded transition">
                                    Activate
                                </button>
                            </form>
                            <form action="{{ route('admin.themes.destroy', $theme) }}" method="POST" 
                                  onsubmit="return confirm('Delete this theme?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-paint-brush text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No themes found. Click "Scan Themes" to discover themes.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Upload Theme Modal (WordPress-like) --}}
<div id="upload-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('upload-modal').classList.add('hidden')"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.themes.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-upload text-purple-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Upload Theme
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 mb-4">
                                    Upload a theme ZIP file (max 50MB). The theme will be automatically installed and registered.
                                </p>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-500 transition">
                                    <input type="file" name="theme_zip" id="theme_zip" accept=".zip" required 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    <p class="text-xs text-gray-500 mt-2">ZIP file only</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Install Theme
                    </button>
                    <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function scanThemes() {
    if (confirm('Scan themes directory for new themes?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.themes.scan') }}';
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection

