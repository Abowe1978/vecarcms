@props(['name' => null, 'label' => null, 'value' => null, 'help' => null, 'source' => 'media_library', 'mode' => 'standalone'])

@if($mode === 'selector')
<div
    x-data="mediaLibrary('{{ $name }}', '{{ $value }}', '{{ $source }}')"
    x-init="initialize"
    class="space-y-2"
>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    
    <!-- Preview Area -->
    <div 
        x-show="preview"
        class="relative w-full aspect-video bg-gray-100 rounded-lg overflow-hidden"
    >
        <img 
            :src="preview ? (preview.startsWith('http') ? preview : '/storage/' + preview) : ''"
            class="w-full h-full object-cover"
            alt="Preview"
        >
        <button 
            @click="removeImage"
            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
            type="button"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Upload Area -->
    <div 
        x-show="!preview"
        @drop.prevent="handleDrop($event)"
        @dragover.prevent="dragOver = true"
        @dragleave.prevent="dragOver = false"
        :class="{'border-blue-500 bg-blue-50': dragOver}"
        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg transition-colors duration-200"
    >
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600">
                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <span>{{ __('admin.posts.upload') }}</span>
                    <input 
                        type="file" 
                        name="{{ $name }}" 
                        class="sr-only"
                        accept="image/*"
                        @change="handleFileSelect($event)"
                    >
                </label>
                <p class="pl-1">{{ __('admin.posts.featured_image_help') }}</p>
            </div>
            <button 
                type="button"
                @click="openMediaLibrary"
                class="mt-2 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                {{ __('admin.media.choose_existing') }}
            </button>
        </div>
    </div>

    <!-- Media Library Modal -->
    <div
        x-show="isModalOpen"
        class="fixed inset-0 z-50 overflow-y-auto"
        x-cloak
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div
                x-show="isModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity"
                @click="closeMediaLibrary"
            >
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div
                x-show="isModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ __('admin.media.library') }}
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="text" 
                                        x-model="searchTerm"
                                        @input="filterMedia"
                                        placeholder="{{ __('admin.media.search') }}"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    >
                                </div>
                            </div>
                            <div class="grid grid-cols-4 gap-4" id="media-library-grid">
                                <template x-for="image in filteredMedia" :key="image.id">
                                    <div 
                                        @click="selectImage(image)"
                                        class="relative aspect-square cursor-pointer group"
                                    >
                                        <img 
                                            :src="image.url"
                                            :alt="image.name"
                                            class="w-full h-full object-cover rounded-lg"
                                        >
                                        <div 
                                            class="absolute inset-0 bg-black bg-opacity-50 group-hover:opacity-100 opacity-0 transition-opacity duration-200 rounded-lg flex items-center justify-center"
                                        >
                                            <div class="text-center text-white">
                                                <p class="text-sm font-medium" x-text="image.name"></p>
                                                <p class="text-xs" x-text="image.size"></p>
                                                <span class="mt-2 inline-block px-2 py-1 text-xs bg-blue-500 rounded">{{ __('admin.media.select') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="closeMediaLibrary"
                    >
                        {{ __('admin.common.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" :name="inputName" x-model="selectedImage">
</div>
@else
<!-- Upload Modal -->
<div x-data="{ 
    isOpen: false,
    isUploading: false,
    progress: 0,
    uploadQueue: [],
    
    init() {
        this.$watch('uploadQueue', value => {
            if (value.length > 0 && !this.isUploading) {
                this.processQueue();
            }
        });
        
        window.addEventListener('open-media-upload', () => {
            this.isOpen = true;
        });
    },
    
    handleFiles(e) {
        const files = Array.from(e.target.files);
        this.uploadQueue.push(...files);
        e.target.value = ''; // Reset input
    },
    
    async processQueue() {
        if (this.uploadQueue.length === 0 || this.isUploading) return;
        
        this.isUploading = true;
        this.progress = 0;
        
        const file = this.uploadQueue[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('source', '{{ $source }}');
        
        try {
            const response = await fetch('{{ route('admin.media.upload') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (!response.ok) throw new Error('Upload failed');
            
            const result = await response.json();
            if (result.success) {
                // Remove the uploaded file from queue
                this.uploadQueue.shift();
                
                if (this.uploadQueue.length === 0) {
                    // Reload the page to show new media
                    window.location.reload();
                } else {
                    // Process next file
                    this.processQueue();
                }
            }
        } catch (error) {
            console.error('Upload error:', error);
            alert('{{ __('admin.media.upload_error') }}');
            this.uploadQueue = [];
        } finally {
            this.isUploading = false;
            this.progress = 0;
        }
    },
    
    dropHandler(e) {
        e.preventDefault();
        
        if (e.dataTransfer.items) {
            [...e.dataTransfer.items].forEach((item) => {
                if (item.kind === 'file') {
                    const file = item.getAsFile();
                    this.uploadQueue.push(file);
                }
            });
        }
    },
    
    dragOverHandler(e) {
        e.preventDefault();
    }
}" 
    @keydown.escape.window="isOpen = false">
    
    <!-- Modal -->
    <div x-show="isOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('admin.media.upload_files') }}
                    </h3>
                    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <!-- Drop Zone -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                         @drop="dropHandler($event)"
                         @dragover="dragOverHandler($event)">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">
                            {{ __('admin.media.drop_files') }}
                        </p>
                        <p class="text-sm text-gray-500 mb-4">
                            {{ __('admin.media.or') }}
                        </p>
                        <input type="file" 
                               @change="handleFiles($event)" 
                               class="hidden" 
                               multiple 
                               accept="image/*"
                               id="fileInput">
                        <label for="fileInput" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg cursor-pointer inline-flex items-center">
                            <i class="fas fa-folder-open mr-2"></i>
                            {{ __('admin.media.browse_files') }}
                        </label>
                    </div>
                    
                    <!-- Upload Progress -->
                    <div x-show="uploadQueue.length > 0" class="mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">
                                {{ __('admin.media.uploading') }} <span x-text="uploadQueue.length"></span> {{ __('admin.media.files') }}
                            </span>
                            <button @click="uploadQueue = []" 
                                    class="text-sm text-red-600 hover:text-red-700">
                                {{ __('admin.media.cancel') }}
                            </button>
                        </div>
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                 :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function mediaLibrary(inputName, initialValue, source) {
    return {
        inputName,
        preview: initialValue,
        selectedImage: initialValue,
        isModalOpen: false,
        dragOver: false,
        mediaLibrary: [],
        filteredMedia: [],
        searchTerm: '',
        source,
        
        async initialize() {
            if (this.preview) {
                this.selectedImage = this.preview;
            }
            await this.loadMediaLibrary();
        },

        async loadMediaLibrary() {
            try {
                const response = await fetch(`/admin/media/list?source=${this.source}`);
                const data = await response.json();
                this.mediaLibrary = data;
                this.filteredMedia = data;
            } catch (error) {
                console.error('Error loading media library:', error);
            }
        },

        filterMedia() {
            if (!this.searchTerm) {
                this.filteredMedia = this.mediaLibrary;
                return;
            }
            
            const search = this.searchTerm.toLowerCase();
            this.filteredMedia = this.mediaLibrary.filter(item => 
                item.name.toLowerCase().includes(search)
            );
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.uploadFile(file);
            }
        },

        handleDrop(event) {
            this.dragOver = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.uploadFile(file);
            }
        },

        async uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('source', this.source);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('/admin/media/upload', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    this.preview = data.location;
                    this.selectedImage = data.path;
                    await this.loadMediaLibrary();
                }
            } catch (error) {
                console.error('Error uploading file:', error);
            }
        },

        removeImage() {
            this.preview = null;
            this.selectedImage = null;
        },

        openMediaLibrary() {
            this.isModalOpen = true;
        },

        closeMediaLibrary() {
            this.isModalOpen = false;
            this.searchTerm = '';
            this.filterMedia();
        },

        selectImage(image) {
            this.preview = image.url;
            this.selectedImage = image.path;
            this.closeMediaLibrary();
        }
    }
}
</script>
@endpush 