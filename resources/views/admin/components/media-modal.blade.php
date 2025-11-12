<div
    x-data="mediaModal"
    x-init="initialize"
    @open-media-modal.window="openModal($event.detail)"
    x-cloak
>
    <!-- Overlay modale -->
    <div 
        x-show="isOpen" 
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Overlay scuro -->
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            
            <!-- Contenuto modale -->
            <div 
                class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[80vh] overflow-hidden"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-medium text-gray-900">{{ __('admin.media.title') }}</h3>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input 
                                type="text" 
                                placeholder="{{ __('admin.media.search') }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10"
                                x-model="searchTerm"
                                @input="filterMedia"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <button 
                            @click="closeModal"
                            class="text-gray-400 hover:text-gray-500"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenuto Media Library -->
                <div class="flex h-[calc(80vh-120px)]">
                    <!-- Pannello sinistro: Media Library Grid -->
                    <div class="w-3/4 p-6 overflow-y-auto">
                        <div class="grid grid-cols-4 gap-4">
                            <template x-for="item in filteredMedia" :key="item.id">
                                <div 
                                    @click="selectMedia(item)"
                                    class="relative aspect-square cursor-pointer group rounded-lg overflow-hidden border border-gray-200"
                                    :class="{'ring-2 ring-blue-500': isMediaSelected(item.id)}"
                                >
                                    <img 
                                        :src="item.url"
                                        :alt="item.name"
                                        class="w-full h-full object-cover"
                                    >
                                    <!-- Indicatore di selezione per la galleria -->
                                    <div 
                                        x-show="mode === 'gallery' && isMediaSelected(item.id)"
                                        class="absolute top-2 right-2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div 
                                        class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                                    >
                                        <div class="text-white text-center p-2">
                                            <p class="text-sm font-medium truncate max-w-full" x-text="item.name"></p>
                                            <span class="text-xs" x-text="item.size"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- Messaggio per nessun risultato -->
                            <div 
                                x-show="filteredMedia.length === 0" 
                                class="col-span-4 py-12 text-center text-gray-500"
                            >
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2">{{ __('admin.media.no_media') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pannello destro: Upload -->
                    <div class="w-1/4 border-l border-gray-200 p-6 bg-gray-50">
                        <h4 class="font-medium text-gray-900 mb-4">{{ __('admin.media.upload') }}</h4>
                        
                        <!-- Area upload -->
                        <div 
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center"
                            @dragover.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="handleDrop"
                            :class="{'border-blue-500 bg-blue-50': dragOver}"
                        >
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">{{ __('admin.media.drop_files') }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ __('admin.media.or') }}</p>
                            
                            <input 
                                type="file" 
                                id="mediaUpload" 
                                class="hidden" 
                                accept="image/*" 
                                @change="handleFileSelect"
                            >
                            <label 
                                for="mediaUpload" 
                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                {{ __('admin.media.browse_files') }}
                            </label>
                        </div>
                        
                        <!-- Upload progress -->
                        <div x-show="isUploading" class="mt-4">
                            <div class="flex justify-between text-xs text-gray-700 mb-1">
                                <span>{{ __('admin.media.uploading') }}...</span>
                                <span x-text="Math.round(uploadProgress) + '%'"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${uploadProgress}%`"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer con azioni -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end border-t border-gray-200">
                    <button 
                        @click="closeModal" 
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50"
                    >
                        {{ __('admin.common.cancel') }}
                    </button>
                    <button 
                        @click="confirmSelection"
                        class="ml-3 inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        :disabled="mode === 'single' ? !selectedMediaId : selectedMediaIds.length === 0"
                        :class="{'opacity-50 cursor-not-allowed': mode === 'single' ? !selectedMediaId : selectedMediaIds.length === 0}"
                    >
                        {{ __('admin.media.select') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaModal', () => ({
        isOpen: false,
        mediaLibrary: [],
        filteredMedia: [],
        searchTerm: '',
        selectedMediaId: null,
        selectedMedia: null,
        callback: null,
        isUploading: false,
        uploadProgress: 0,
        dragOver: false,
        uploadedFiles: [],
        mode: 'single', // 'single' or 'gallery'
        selectedMediaIds: [], // for gallery mode
        
        initialize() {
            this.loadMediaLibrary();
        },
        
        async loadMediaLibrary() {
            try {
                const response = await fetch("{{ route('admin.media.list') }}");
                const data = await response.json();
                this.mediaLibrary = data;
                this.filteredMedia = data;
            } catch (error) {
                console.error('Error loading media library', error);
            }
        },
        
        openModal(detail) {
            this.isOpen = true;
            this.callback = detail?.callback || null;
            this.mode = detail?.mode || 'single';
            this.selectedMediaId = null;
            this.selectedMedia = null;
            this.selectedMediaIds = [];
            this.uploadedFiles = [];
            
            // Reload the Media Library each time we open the modal
            this.loadMediaLibrary();
        },
        
        closeModal() {
            this.isOpen = false;
            this.searchTerm = '';
            this.filterMedia();
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
        
        selectMedia(media) {
            if (this.mode === 'single') {
                this.selectedMediaId = media.id;
                this.selectedMedia = media;
            } else if (this.mode === 'gallery') {
                // Toggle selection for gallery mode
                const index = this.selectedMediaIds.indexOf(media.id);
                if (index === -1) {
                    this.selectedMediaIds.push(media.id);
                    console.log('Added image to gallery selection:', media.id, 'Total:', this.selectedMediaIds.length);
                } else {
                    this.selectedMediaIds.splice(index, 1);
                    console.log('Removed image from gallery selection:', media.id, 'Total:', this.selectedMediaIds.length);
                }
            }
        },
        
        isMediaSelected(mediaId) {
            if (this.mode === 'single') {
                return this.selectedMediaId === mediaId;
            } else {
                return this.selectedMediaIds.includes(mediaId);
            }
        },
        
        confirmSelection() {
            console.log('Confirming selection. Mode:', this.mode);
            console.log('Selected IDs:', this.selectedMediaIds);
            
            if (this.mode === 'single' && this.selectedMedia && this.callback) {
                this.callback(this.selectedMedia);
                this.closeModal();
            } else if (this.mode === 'gallery' && this.selectedMediaIds.length > 0 && this.callback) {
                // Filtra esplicitamente solo le immagini selezionate
                const selectedGalleryItems = this.mediaLibrary.filter(item => {
                    const isSelected = this.selectedMediaIds.includes(item.id);
                    console.log('Image ID:', item.id, 'Selected:', isSelected);
                    return isSelected;
                });
                
                console.log('Selected gallery items:', selectedGalleryItems.length);
                
                if (selectedGalleryItems.length > 0) {
                    this.callback(selectedGalleryItems);
                    this.closeModal();
                } else {
                    console.error('No images selected for gallery');
                }
            }
        },
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.uploadFile(file);
            }
        },
        
        handleDrop(event) {
            this.dragOver = false;
            
            if (event.dataTransfer.files.length > 0) {
                const file = event.dataTransfer.files[0];
                this.uploadFile(file);
            }
        },
        
        async uploadFile(file) {
            // Preveniamo il doppio caricamento con un ID univoco basato su nome e dimensione file
            const fileId = `${file.name}-${file.size}-${file.lastModified}`;
            
            // Se questo file è stato già caricato, non procediamo
            if (this.uploadedFiles && this.uploadedFiles.includes(fileId)) {
                console.log('File già caricato, evito il doppio caricamento');
                return;
            }
            
            // Aggiungiamo il file all'elenco dei file caricati
            if (!this.uploadedFiles) this.uploadedFiles = [];
            this.uploadedFiles.push(fileId);
            
            this.isUploading = true;
            this.uploadProgress = 0;
            
            const formData = new FormData();
            formData.append('file', file);
            formData.append('source', 'media_library');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            try {
                // Usando XMLHttpRequest per monitorare il progresso
                const xhr = new XMLHttpRequest();
                
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.uploadProgress = (e.loaded / e.total) * 100;
                    }
                });
                
                xhr.onload = async () => {
                    if (xhr.status === 200) {
                        const result = JSON.parse(xhr.responseText);
                        if (result.success) {
                            // Attendiamo un attimo e ricarichiamo la libreria
                            setTimeout(() => {
                                this.loadMediaLibrary();
                                this.isUploading = false;
                            }, 500);
                        }
                    } else {
                        console.error('Upload error:', xhr.statusText);
                        this.isUploading = false;
                    }
                };
                
                xhr.onerror = () => {
                    console.error('Upload error');
                    this.isUploading = false;
                };
                
                xhr.open('POST', '/admin/media/upload', true);
                xhr.send(formData);
                
            } catch (error) {
                console.error('Error uploading file:', error);
                this.isUploading = false;
            }
        }
    }));
});
</script>
@endpush 