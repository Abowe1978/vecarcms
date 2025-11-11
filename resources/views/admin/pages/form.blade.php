<!-- Campi per il form delle pagine -->

<div class="flex gap-6">
    <!-- Main Content Column -->
    <div class="flex-1 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.pages.page_information') }}</h2>
            
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">{{ __('admin.pages.title_label') }}</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.pages.title_placeholder') }}" value="{{ old('title', $page->title ?? '') }}" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <label for="slug" class="block text-sm font-medium text-gray-700">Permalink</label>
                    <button type="button" id="edit-slug-btn" class="text-sm text-blue-600 hover:text-blue-800">
                        {{ __('admin.pages.edit_slug') }}
                    </button>
                </div>
                <div class="mt-1 flex items-center">
                    <span class="text-sm text-gray-500 mr-1">{{ config('app.url') }}/</span>
                    <div id="slug-display" class="text-sm text-gray-700"></div>
                    <div id="slug-edit-container" class="flex-1 hidden">
                        <input type="text" name="slug" id="slug" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('slug', $page->slug ?? '') }}">
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
            @include('admin.components.editor-toggle', ['model' => $page ?? new \App\Models\Page()])

            <!-- SEO Section -->
            <div class="border-t border-gray-200 pt-4 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.seo.title') }}</h3>
                
                <!-- Meta Title -->
                <div class="mb-4">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_title') }}</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.seo.meta_title_placeholder') }}">
                    <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_title_help') }}</p>
                </div>

                <!-- Meta Description -->
                <div class="mb-4">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_description') }}</label>
                    <textarea id="meta_description" name="meta_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.seo.meta_description_placeholder') }}">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_description_help') }}</p>
                </div>

                <!-- Meta Keywords -->
                <div class="mb-4">
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_keywords') }}</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('admin.seo.meta_keywords_placeholder') }}">
                    <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_keywords_help') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Column -->
    <div class="w-80 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">{{ __('admin.pages.status') }}</label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }}>
                        <label for="is_published" class="ml-2 block text-sm text-gray-900">
                            {{ __('admin.pages.publish') }}
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_homepage" id="is_homepage" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_homepage', $page->is_homepage ?? false) ? 'checked' : '' }}>
                        <label for="is_homepage" class="ml-2 block text-sm text-gray-900">
                            {{ __('admin.pages.set_as_homepage') }}
                        </label>
                    </div>
                    @if(isset($page) && $page->is_homepage)
                        <p class="text-xs text-green-600 ml-6"><i class="fas fa-check-circle mr-1"></i>This is the current homepage</p>
                    @endif
                    <div class="flex items-center">
                        <input type="checkbox" name="is_blog" id="is_blog" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_blog', $page->is_blog ?? false) ? 'checked' : '' }}>
                        <label for="is_blog" class="ml-2 block text-sm text-gray-900">
                            {{ __('admin.pages.set_as_blog') }}
                        </label>
                    </div>
                    @if(isset($page) && $page->is_blog)
                        <p class="text-xs text-blue-600 ml-6"><i class="fas fa-check-circle mr-1"></i>This is the current blog page</p>
                    @endif
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="show_title"
                            id="show_title"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            {{ old('show_title', isset($page) ? $page->show_title : true) ? 'checked' : '' }}
                        >
                        <label for="show_title" class="ml-2 block text-sm text-gray-900">
                            {{ __('admin.pages.show_title_label') }}
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 ml-6">{{ __('admin.pages.show_title_help') }}</p>
                </div>
            </div>

            <!-- Order -->
            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-700">{{ __('admin.pages.order_label') }}</label>
                <input type="number" name="order" id="order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('order', $page->order ?? 0) }}" min="0">
                <p class="mt-1 text-sm text-gray-500">{{ __('admin.pages.order_help') }}</p>
            </div>

            <!-- Parent Page -->
            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">{{ __('admin.pages.parent_label') }}</label>
                <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">{{ __('admin.pages.no_parent') }}</option>
                    @foreach($parentPages ?? [] as $parentPage)
                        <option value="{{ $parentPage->id }}" {{ old('parent_id', $page->parent_id ?? '') == $parentPage->id ? 'selected' : '' }}>
                            {{ $parentPage->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Template -->
            <div class="mb-4">
                <label for="template" class="block text-sm font-medium text-gray-700">{{ __('admin.pages.template_label') }}</label>
                <select name="template" id="template" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($templates ?? [] as $key => $name)
                        <option value="{{ $key }}" {{ old('template', $page->template ?? 'default') == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Featured Image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('admin.pages.featured_image') }}
                </label>
                
                <div x-data="mediaSelector('featured_image', '{{ $page->featured_image ?? '' }}')" class="space-y-2">
                    <!-- Anteprima immagine selezionata -->
                    <div 
                        x-show="preview" 
                        class="relative w-full aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200"
                    >
                        <img 
                            x-bind:src="getImageUrl()" 
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
                        {{ __('admin.pages.change_image') }}
                    </button>
                    
                    <input type="hidden" name="featured_image" x-model="selectedImage">
                </div>
                
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 mt-6">
                <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('admin.cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('admin.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
