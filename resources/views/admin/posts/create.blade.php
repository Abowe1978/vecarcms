@extends('layouts.admin')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.posts.create_title') }}</h1>
        <a href="{{ route('admin.posts.index') }}" class="text-blue-600 hover:text-blue-800">{{ __('admin.posts.back_to_posts') }}</a>
    </div>

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="flex gap-6">
            <!-- Main Content Column -->
            <div class="flex-1 space-y-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.posts.post_information') }}</h2>
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">{{ __('admin.posts.title_label') }}</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.posts.title_placeholder') }}" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Permalink</label>
                            <button type="button" id="edit-slug-btn" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ __('admin.posts.edit_slug') }}
                            </button>
                        </div>
                        <div class="mt-1 flex items-center">
                            <span class="text-sm text-gray-500 mr-1">{{ config('app.url') }}/</span>
                            <div id="slug-display" class="text-sm text-gray-700"></div>
                            <div id="slug-edit-container" class="flex-1 hidden">
                                <input type="text" name="slug" id="slug" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('slug') }}">
                            </div>
                            <button type="button" id="save-slug-btn" class="ml-2 text-sm text-blue-600 hover:text-blue-800 hidden">
                                {{ __('admin.save') }}
                            </button>
                            <button type="button" id="cancel-slug-btn" class="ml-2 text-sm text-red-600 hover:text-red-800 hidden">
                                {{ __('admin.cancel') }}
                            </button>
                        </div>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Editor Toggle -->
                    @include('admin.components.editor-toggle', ['model' => new \App\Models\Post()])

                    <div class="mb-4">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700">{{ __('admin.posts.excerpt_label') }}</label>
                        <textarea name="excerpt" id="excerpt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3" placeholder="{{ __('admin.posts.excerpt_placeholder') }}">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t border-gray-200 pt-4 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.seo.title') }}</h3>
                        
                        <!-- Meta Title -->
                        <div class="mb-4">
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_title') }}</label>
                            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.seo.meta_title_placeholder') }}">
                            <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_title_help') }}</p>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-4">
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_description') }}</label>
                            <textarea id="meta_description" name="meta_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.seo.meta_description_placeholder') }}">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_description_help') }}</p>
                        </div>

                        <!-- Meta Keywords (Hidden) -->
                        <div class="mb-4" style="display: none;">
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_keywords') }}</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.seo.meta_keywords_placeholder') }}">
                            <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_keywords_help') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="w-80 space-y-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.posts.status') }}</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('admin.posts.status_draft') }}</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>{{ __('admin.posts.status_published') }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="published_at" class="block text-sm font-medium text-gray-700">{{ __('admin.posts.published_at') }}</label>
                        <input type="datetime-local" name="published_at" id="published_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('published_at') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.posts.featured_image') }}
                        </label>
                        
                        <div x-data="mediaSelector('featured_image')" class="space-y-2">
                            <!-- Anteprima immagine selezionata -->
                            <div 
                                x-show="preview" 
                                class="relative w-full aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200"
                            >
                                <img 
                                    x-bind:src="preview ? '/storage/' + preview : ''" 
                                    class="w-full h-full object-cover" 
                                    alt="Featured image preview"
                                >
                                <button 
                                    @click.prevent="removeImage" 
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                    type="button"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Pulsante selezione immagine -->
                            <button 
                                x-show="!preview"
                                @click.prevent="openMediaLibrary"
                                type="button" 
                                class="w-full flex justify-center items-center px-6 py-3 border-2 border-gray-300 border-dashed rounded-lg text-sm text-gray-600 hover:bg-gray-50"
                            >
                                <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('admin.media.choose_existing') }} / {{ __('admin.media.upload') }}
                            </button>
                            
                            <!-- Pulsante per cambiare immagine quando c'è già una preview -->
                            <button 
                                x-show="preview"
                                @click.prevent="openMediaLibrary"
                                type="button" 
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                {{ __('admin.posts.change_image') }}
                            </button>
                            
                            <input type="hidden" name="featured_image" x-model="selectedImage">
                        </div>
                        
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('admin.posts.categories') }}
                        </label>
                        <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            @livewire('category-selector')
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('admin.posts.tags') }}
                        </label>
                        <div class="mt-2">
                            @livewire('tag-selector')
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="submit" name="action" value="draft" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('admin.posts.save_draft') }}
                        </button>
                        <button type="submit" name="action" value="publish" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('admin.posts.publish') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<x-media-modal />

@push('scripts')
<script src="{{ asset('build/vendor/tinymce/tinymce.min.js') }}"></script>
<script>
    if (window.tinymce) {
        tinymce.baseURL = "{{ asset('build/vendor/tinymce') }}";
    }
</script>
<script>
    function mediaSelector(inputName, initialValue = '') {
        return {
            inputName: inputName,
            selectedImage: initialValue,
            preview: initialValue,
            
            openMediaLibrary() {
                // Trigger the custom event to open the Media Library
                window.dispatchEvent(new CustomEvent('open-media-modal', {
                    detail: {
                        callback: (media) => {
                            this.selectedImage = media.path;
                            this.preview = media.path;
                        }
                    }
                }));
            },
            
            removeImage() {
                this.selectedImage = '';
                this.preview = '';
            }
        };
    }

    // Funzioni per l'inserimento di media nell'editor
    document.getElementById('add-media-btn').addEventListener('click', function() {
        window.dispatchEvent(new CustomEvent('open-media-modal', {
            detail: {
                mode: 'single',
                callback: (media) => {
                    // Inserisci l'immagine nell'editor TinyMCE
                    const imageHtml = `<img src="${media.url}" alt="${media.name}" class="img-fluid">`;
                    tinymce.activeEditor.execCommand('mceInsertContent', false, imageHtml);
                }
            }
        }));
    });

    // Slug management
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const slugDisplay = document.getElementById('slug-display');
        const editSlugBtn = document.getElementById('edit-slug-btn');
        const saveSlugBtn = document.getElementById('save-slug-btn');
        const cancelSlugBtn = document.getElementById('cancel-slug-btn');
        const slugEditContainer = document.getElementById('slug-edit-container');
        
        let originalSlug = '';
        
        // Function to convert string to slug
        function stringToSlug(str) {
            return str
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
        
        // Update slug when title changes
        titleInput.addEventListener('input', function() {
            const newSlug = stringToSlug(this.value);
            slugInput.value = newSlug;
            slugDisplay.textContent = newSlug;
        });
        
        // Enter edit mode
        editSlugBtn.addEventListener('click', function() {
            originalSlug = slugInput.value;
            slugDisplay.classList.add('hidden');
            slugEditContainer.classList.remove('hidden');
            editSlugBtn.classList.add('hidden');
            saveSlugBtn.classList.remove('hidden');
            cancelSlugBtn.classList.remove('hidden');
        });
        
        // Save slug
        saveSlugBtn.addEventListener('click', function() {
            const newSlug = stringToSlug(slugInput.value);
            slugInput.value = newSlug;
            slugDisplay.textContent = newSlug;
            
            slugDisplay.classList.remove('hidden');
            slugEditContainer.classList.add('hidden');
            saveSlugBtn.classList.add('hidden');
            cancelSlugBtn.classList.add('hidden');
            editSlugBtn.classList.remove('hidden');
        });
        
        // Cancel edit
        cancelSlugBtn.addEventListener('click', function() {
            slugInput.value = originalSlug;
            slugDisplay.textContent = originalSlug;
            
            slugDisplay.classList.remove('hidden');
            slugEditContainer.classList.add('hidden');
            saveSlugBtn.classList.add('hidden');
            cancelSlugBtn.classList.add('hidden');
            editSlugBtn.classList.remove('hidden');
        });
        
        // Initialize slug from title if empty
        if (titleInput.value && !slugInput.value) {
            const newSlug = stringToSlug(titleInput.value);
            slugInput.value = newSlug;
            slugDisplay.textContent = newSlug;
        } else if (slugInput.value) {
            slugDisplay.textContent = slugInput.value;
        }
    });

    tinymce.init({
        selector: '#content',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        height: 500,
        menubar: true,
        branding: false,
        promotion: false,
        relative_urls: false,
        convert_urls: false,
        image_caption: true,
        automatic_uploads: true,
        images_upload_url: '{{ route('admin.media.upload') }}',
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.media.upload') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.location);
            };
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }
    });
</script>
@endpush
@endsection 