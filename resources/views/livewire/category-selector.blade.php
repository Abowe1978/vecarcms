<div>
    @if($message)
        <div class="mb-4 p-2 rounded-md {{ $messageType === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
            {{ $message }}
        </div>
    @endif

    <div class="space-y-2">
        @foreach($categories as $category)
            <div class="flex items-center">
                <input type="checkbox" 
                       wire:model.live="selectedCategories"
                       value="{{ $category->id }}"
                       id="category-{{ $category->id }}" 
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="category-{{ $category->id }}" class="ml-2 flex items-center">
                    <div class="w-3 h-3 rounded mr-2" style="background-color: {{ $category->color }}"></div>
                    <span class="text-sm text-gray-700">{{ $category->name }}</span>
                </label>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        @if(!$showNewCategoryInput)
            <button type="button" 
                    wire:click="toggleNewCategoryInput"
                    class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('admin.categories.quick_add') }}
            </button>
        @else
            <div class="space-y-2">
                <input type="text" 
                       wire:model="newCategoryName"
                       wire:keydown.enter="addNewCategory"
                       placeholder="{{ __('admin.categories.fields.name') }}"
                       class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                
                <div class="flex items-center space-x-2">
                    <button type="button" 
                            wire:click="addNewCategory"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('admin.save') }}
                    </button>
                    <button type="button" 
                            wire:click="toggleNewCategoryInput"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('admin.cancel') }}
                    </button>
                </div>

                @error('newCategoryName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>

    <!-- Hidden input to sync with form -->
    @foreach($selectedCategories as $categoryId)
        <input type="hidden" name="categories[]" value="{{ $categoryId }}">
    @endforeach
</div>
