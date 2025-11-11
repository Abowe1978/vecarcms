@if($message)
    <div class="mb-4 {{ $messageType === 'success' ? 'text-green-600' : 'text-red-600' }}">
        {{ $message }}
    </div>
@endif

<div class="space-y-4">
    <!-- Selected Tags Input -->
    <div>
        <input type="text" 
               wire:model.live="tags" 
               wire:change="updateTagsFromInput"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
               placeholder="{{ __('admin.posts.tags_placeholder') }}">
        <p class="mt-1 text-sm text-gray-500">{{ __('admin.posts.tags_help') }}</p>
    </div>

    <!-- Selected Tags Pills -->
    @if(count($selectedTags) > 0)
        <div class="flex flex-wrap gap-2">
            @foreach($selectedTags as $tag)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                    {{ $tag }}
                    <button type="button" wire:click="removeTag('{{ $tag }}')" class="ml-2 inline-flex items-center p-0.5 rounded-full text-primary-400 hover:bg-primary-200 hover:text-primary-500 focus:outline-none">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
            @endforeach
        </div>
    @endif

    <!-- Popular Tags Cloud -->
    <div class="border-t border-gray-200 pt-4">
        <h4 class="text-sm font-medium text-gray-900 mb-3">{{ __('admin.posts.popular_tags') }}</h4>
        @if(count($popularTags) > 0)
            <div class="flex flex-wrap justify-center gap-3 py-4">
                @foreach($popularTags as $tag)
                    <button type="button"
                            wire:click="addTag('{{ $tag['name'] }}')"
                            class="inline-flex items-center px-3 py-1 rounded-full hover:bg-gray-50 transition-colors duration-150 ease-in-out"
                            style="font-size: {{ $tag['font_size'] }}px; color: {{ $tag['color'] ?? '#4A5568' }}">
                        {{ $tag['name'] }}
                    </button>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">{{ __('admin.posts.no_popular_tags') }}</p>
        @endif
    </div>

    <!-- Quick Add Tag -->
    <div class="pt-2">
        <button type="button" wire:click="toggleNewTagInput" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <svg class="mr-2 -ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('admin.tags.quick_add') }}
        </button>

        @if($showNewTagInput)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="space-y-4">
                    <div>
                        <label for="newTagName" class="block text-sm font-medium text-gray-700">{{ __('admin.tags.fields.name') }}</label>
                        <div class="mt-1">
                            <input type="text" 
                                   id="newTagName"
                                   wire:model="newTagName" 
                                   wire:keydown.enter="addNewTag" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" 
                                   placeholder="{{ __('admin.tags.fields.name') }}" />
                        </div>
                        @error('newTagName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                wire:click="toggleNewTagInput" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="button" 
                                wire:click="addNewTag" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            {{ __('admin.save') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Hidden input for form submission -->
    <input type="hidden" name="tags" value="{{ $tags }}">
</div>
