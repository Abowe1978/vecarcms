@extends('layouts.admin')

@section('title', 'Plugins')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    
    <!-- Page Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Plugins</h1>
            <p class="text-sm text-gray-600 mt-1">Gestisci i plugin installati nel tuo sito</p>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <!-- Scan Plugins -->
            <form method="POST" action="{{ route('admin.plugins.scan') }}" class="inline">
                @csrf
                <button type="submit" class="btn bg-gray-600 hover:bg-gray-700 text-white">
                    <i class="fas fa-sync-alt mr-2"></i>
                    <span>Scan Plugins</span>
                </button>
            </form>

            <!-- Upload Plugin -->
            <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" 
                    class="btn bg-purple-600 hover:bg-purple-700 text-white">
                <i class="fas fa-upload mr-2"></i>
                <span>Upload Plugin</span>
            </button>
        </div>
    </div>

    @if($plugins->isEmpty())
        <!-- Empty State -->
        <div class="bg-white shadow-lg rounded-lg p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-100 mb-4">
                <i class="fas fa-plug text-3xl text-purple-600"></i>
            </div>
            
            <h2 class="text-xl font-bold text-gray-800 mb-2">Nessun Plugin Installato</h2>
            <p class="text-gray-600 mb-6">
                Installa il tuo primo plugin per estendere le funzionalità del CMS
            </p>

            <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" 
                    class="btn bg-purple-600 hover:bg-purple-700 text-white">
                <i class="fas fa-upload mr-2"></i>
                Carica Plugin
            </button>
        </div>
    @else
        <!-- Plugins List -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @foreach($plugins as $plugin)
                <div class="border-b border-gray-200 last:border-b-0">
                    <div class="p-6 flex items-center justify-between">
                        <!-- Plugin Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $plugin->name }}</h3>
                                @if($plugin->is_active)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Attivo
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inattivo
                                    </span>
                                @endif
                                @if($plugin->version)
                                    <span class="text-sm text-gray-500">v{{ $plugin->version }}</span>
                                @endif
                            </div>

                            @if($plugin->description)
                                <p class="text-gray-600 mb-2">{{ $plugin->description }}</p>
                            @endif

                            <div class="text-sm text-gray-500">
                                @if($plugin->author)
                                    <span>
                                        Di 
                                        @if($plugin->author_uri)
                                            <a href="{{ $plugin->author_uri }}" target="_blank" class="text-purple-600 hover:text-purple-700">
                                                {{ $plugin->author }}
                                            </a>
                                        @else
                                            {{ $plugin->author }}
                                        @endif
                                    </span>
                                @endif
                                @if($plugin->plugin_uri)
                                    <span class="mx-2">|</span>
                                    <a href="{{ $plugin->plugin_uri }}" target="_blank" class="text-purple-600 hover:text-purple-700">
                                        Visita sito plugin
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            @if($plugin->is_active)
                                <!-- Deactivate -->
                                <form method="POST" action="{{ route('admin.plugins.deactivate', $plugin) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded">
                                        Disattiva
                                    </button>
                                </form>

                                <!-- Settings (if available) -->
                                <a href="{{ route('admin.plugins.settings', $plugin) }}" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                    <i class="fas fa-cog"></i>
                                </a>
                            @else
                                <!-- Activate -->
                                <form method="POST" action="{{ route('admin.plugins.activate', $plugin) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                                        Attiva
                                    </button>
                                </form>
                            @endif

                            <!-- Delete -->
                            <form method="POST" action="{{ route('admin.plugins.destroy', $plugin) }}" 
                                  class="inline"
                                  onsubmit="return confirm('Sei sicuro di voler eliminare questo plugin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

<!-- Upload Modal -->
<div id="upload-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Carica Plugin</h3>
            
            <form method="POST" action="{{ route('admin.plugins.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File ZIP del Plugin
                    </label>
                    <input type="file" 
                           name="plugin_zip" 
                           accept=".zip" 
                           required
                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-sm text-gray-500">Carica un file ZIP contenente il plugin (max 10MB)</p>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        Carica
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('upload-modal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annulla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
