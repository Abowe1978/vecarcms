@props(['name' => 'image', 'currentImage' => null, 'label' => 'Image', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200" 
         id="dropzone-{{ $name }}">
        <div class="space-y-1 text-center">
            @if($currentImage)
                <div class="relative">
                    <img src="{{ $currentImage }}" alt="Current image" class="mx-auto h-32 w-32 object-cover rounded-lg">
                    <button type="button" 
                            onclick="removeImage('{{ $name }}')"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                        ×
                    </button>
                </div>
                <p class="text-sm text-gray-600">Click to change image</p>
            @else
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="{{ $name }}" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <span>Upload a file</span>
                        <input id="{{ $name }}" name="{{ $name }}" type="file" class="sr-only" accept="image/*" onchange="previewImage(this, '{{ $name }}')">
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
            @endif
        </div>
    </div>
    
    @if($currentImage)
        <input type="file" id="{{ $name }}" name="{{ $name }}" class="hidden" accept="image/*" onchange="previewImage(this, '{{ $name }}')">
    @else
        <input type="file" id="{{ $name }}" name="{{ $name }}" class="hidden" accept="image/*" onchange="previewImage(this, '{{ $name }}')">
    @endif
</div>

<script>
function previewImage(input, name) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const dropzone = document.getElementById('dropzone-' + name);
            dropzone.innerHTML = `
                <div class="space-y-1 text-center">
                    <div class="relative">
                        <img src="${e.target.result}" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg">
                        <button type="button" 
                                onclick="removeImage('${name}')"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                            ×
                        </button>
                    </div>
                    <p class="text-sm text-gray-600">Click to change image</p>
                </div>
            `;
            
            // Add click handler to change image
            dropzone.onclick = function() {
                document.getElementById(name).click();
            };
            
            // Ensure the input has the correct name attribute
            const input = document.getElementById(name);
            if (input) {
                input.setAttribute('name', name);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(name) {
    const dropzone = document.getElementById('dropzone-' + name);
    const input = document.getElementById(name);
    
    // Reset input
    input.value = '';
    
    // Reset dropzone
    dropzone.innerHTML = `
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600">
                <label for="${name}" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <span>Upload a file</span>
                    <input id="${name}" name="${name}" type="file" class="hidden" accept="image/*" onchange="previewImage(this, '${name}')">
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
        </div>
    `;
    
    // Add click handler
    dropzone.onclick = function() {
        document.getElementById(name).click();
    };
}

// Add drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropzones = document.querySelectorAll('[id^="dropzone-"]');
    
    dropzones.forEach(dropzone => {
        const name = dropzone.id.replace('dropzone-', '');
        
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('border-indigo-400', 'bg-indigo-50');
        });
        
        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
        });
        
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = document.getElementById(name);
                input.files = files;
                previewImage(input, name);
            }
        });
    });
});
</script>
