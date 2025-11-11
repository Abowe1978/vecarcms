<div>
    <label class="block text-sm font-medium text-gray-700">{{ __('admin.vehicles.photos') }}</label>
    
    <div class="space-y-4">
        @foreach($photoInputs as $index => $value)
            <div class="flex items-center space-x-4">
                <div class="flex-grow">
                    <input type="file" 
                           wire:model="photos.{{ $index }}"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100"
                           accept="image/*">
                    @error("photos.$index") 
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                
                @if(count($photoInputs) > 1)
                    <button type="button" 
                            wire:click="removePhotoInput({{ $index }})"
                            class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        @endforeach

        <button type="button"
                wire:click="addPhotoInput"
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('admin.vehicles.add_photo') }}
        </button>
    </div>

    <!-- Hidden inputs to pass the photos to the form -->
    @foreach($photos as $index => $photo)
        @if($photo)
            <input type="hidden" name="photos[]" value="{{ $photo->temporaryUrl() }}">
        @endif
    @endforeach
</div> 