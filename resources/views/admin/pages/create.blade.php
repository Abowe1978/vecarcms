@extends('layouts.admin')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.pages.create_title') }}</h1>
        <a href="{{ route('admin.pages.index') }}" class="text-blue-600 hover:text-blue-800">{{ __('admin.pages.back_to_pages') }}</a>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        @include('admin.pages.form')
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
                            console.log('Selected media:', media);
                            // Utilizza direttamente l'URL completo
                            this.selectedImage = media.url || media.path;
                            this.preview = media.url || media.path;
                        }
                    }
                }));
            },
            
            removeImage() {
                this.selectedImage = '';
                this.preview = '';
            },

            // Computed property per ottenere l'URL corretto dell'immagine
            getImageUrl() {
                if (!this.preview) return '';
                return this.preview;
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
        
        // Generate slug from title
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.value === originalSlug) {
                const newSlug = stringToSlug(titleInput.value);
                slugInput.value = newSlug;
                slugDisplay.textContent = newSlug;
            }
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
        
        // Initialize slug display
        if (slugInput.value) {
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
