{{-- Toggle tra Classic Editor e Page Builder --}}
<div class="mb-4" x-data="{
    usePageBuilder: {{ old('use_page_builder', $model->use_page_builder ?? false) ? 'true' : 'false' }},
    isPageBuilderOpen: false,
    pageBuilderContent: '{{ old('page_builder_content', $model->page_builder_content ? addslashes(json_encode($model->page_builder_content)) : '') }}',
    
    init() {
        console.log('🎯 Alpine component initialized');
        console.log('usePageBuilder:', this.usePageBuilder);
        console.log('pageBuilderContent length:', this.pageBuilderContent.length);
        
        // Auto-open page builder if #page-builder hash is present
        if (window.location.hash === '#page-builder' && this.usePageBuilder) {
            console.log('Auto-opening page builder from hash - waiting for script...');
            // Wait for script to load before opening
            const waitForPageBuilder = setInterval(() => {
                if (window.initPageBuilder) {
                    console.log('✅ PageBuilder script ready, opening editor...');
                    clearInterval(waitForPageBuilder);
                    this.isPageBuilderOpen = true;
                    // Remove hash to clean URL
                    history.replaceState(null, null, window.location.pathname + window.location.search);
                } else {
                    console.log('⏳ Waiting for pagebuilder.js...');
                }
            }, 100);
            
            // Timeout after 5 seconds
            setTimeout(() => {
                clearInterval(waitForPageBuilder);
                if (!window.initPageBuilder) {
                    console.error('❌ PageBuilder script failed to load after 5 seconds');
                }
            }, 5000);
        }
        
        this.$watch('isPageBuilderOpen', (open) => {
            console.log('isPageBuilderOpen changed to:', open);
            if (open) {
                this.$nextTick(() => {
                    console.log('window.initPageBuilder exists:', !!window.initPageBuilder);
                    if (window.initPageBuilder) {
                        console.log('Calling initPageBuilder with content:', this.pageBuilderContent.substring(0, 100) + '...');
                        window.initPageBuilder({
                            initialContent: this.pageBuilderContent,
                            onSave: (json) => {
                                console.log('Page Builder saved:', json);
                                this.pageBuilderContent = json;
                                this.isPageBuilderOpen = false;
                            },
                            onClose: () => {
                                console.log('Page Builder closed');
                                this.isPageBuilderOpen = false;
                            }
                        });
                    } else {
                        console.error('❌ window.initPageBuilder not found! Script not loaded.');
                    }
                });
            }
        });
    }
}">
    {{-- Page Builder buttons hidden per richiesta utente --}}
    {{-- 
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ __('admin.editor.mode_label') }}
    </label>
    
    <div class="flex items-center space-x-4">
        <button
            type="button"
            @click="usePageBuilder = false; isPageBuilderOpen = false"
            :class="!usePageBuilder ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
            class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
        >
            <i class="fas fa-edit"></i>
            <span>{{ __('admin.editor.classic_mode') }}</span>
        </button>
        
        <button
            type="button"
            @click="usePageBuilder = true; isPageBuilderOpen = true"
            :class="usePageBuilder ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
            class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
        >
            <i class="fas fa-th-large"></i>
            <span>{{ __('admin.editor.page_builder_mode') }}</span>
        </button>
    </div>
    --}}
    
    <input type="hidden" name="use_page_builder" :value="usePageBuilder ? '1' : '0'">
    
    {{-- Classic Editor (TinyMCE) --}}
    <div x-show="!usePageBuilder" x-transition class="mt-4">
        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
            {{ __('admin.posts.content_label') }}
        </label>
        <div class="mt-1 mb-2 flex items-center">
            <button 
                type="button" 
                id="add-media-btn"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
                <svg class="mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ __('admin.posts.add_media') }}
            </button>
        </div>
        <textarea 
            name="content" 
            id="content" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
            rows="15"
        >{{ old('content', $model->content ?? '') }}</textarea>
        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    {{-- Hidden input per salvare il contenuto JSON --}}
    <input 
        type="hidden" 
        name="page_builder_content" 
        id="page_builder_content" 
        x-model="pageBuilderContent"
    >
    
    {{-- Page Builder Full-Page Overlay (come Elementor) --}}
    <template x-if="isPageBuilderOpen">
        <div class="fixed inset-0 z-[9999] bg-white flex flex-col" x-transition>
            {{-- Header con titolo e azioni --}}
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 flex items-center justify-between shadow-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-paint-brush text-xl"></i>
                    <h2 class="text-lg font-semibold">{{ __('admin.editor.page_builder_mode') }}</h2>
                </div>
                <div class="flex items-center space-x-3">
                    <button 
                        type="button" 
                        @click="isPageBuilderOpen = false" 
                        class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white font-medium transition-colors duration-200 flex items-center space-x-2"
                    >
                        <i class="fas fa-times"></i>
                        <span>{{ __('admin.editor.close_page_builder') }}</span>
                    </button>
                    <button 
                        type="button" 
                        @click="$dispatch('save-page-builder')" 
                        class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white font-medium transition-colors duration-200 flex items-center space-x-2 shadow-lg"
                    >
                        <i class="fas fa-save"></i>
                        <span>{{ __('admin.editor.save_and_close_page_builder') }}</span>
                    </button>
                </div>
            </div>
            
            {{-- Page Builder Container --}}
            <div id="page-builder-container" class="flex-1 overflow-hidden"></div>
        </div>
    </template>
    
    {{-- Load Page Builder script if this model uses page builder --}}
    @if($model->use_page_builder ?? false)
        @push('pagebuilder-scripts')
            @vite('resources/js/pagebuilder.js')
        @endpush
    @endif
</div>

