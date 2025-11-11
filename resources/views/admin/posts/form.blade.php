    <!-- Excerpt -->
    <div class="mb-6">
        <label for="excerpt" class="block text-sm font-medium text-gray-700">{{ __('admin.posts.excerpt') }}</label>
        <textarea id="excerpt" name="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="{{ __('admin.posts.excerpt_placeholder') }}">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    </div>

    <!-- SEO Section -->
    <div class="border-t border-gray-200 pt-4 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.seo.title') }}</h3>
        
        <!-- Meta Title -->
        <div class="mb-4">
            <label for="meta_title" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_title') }}</label>
            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="{{ __('admin.seo.meta_title_placeholder') }}">
            <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_title_help') }}</p>
        </div>

        <!-- Meta Description -->
        <div class="mb-4">
            <label for="meta_description" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_description') }}</label>
            <textarea id="meta_description" name="meta_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="{{ __('admin.seo.meta_description_placeholder') }}">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
            <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_description_help') }}</p>
        </div>

        <!-- Meta Keywords -->
        <div class="mb-4">
            <label for="meta_keywords" class="block text-sm font-medium text-gray-700">{{ __('admin.seo.meta_keywords') }}</label>
            <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="{{ __('admin.seo.meta_keywords_placeholder') }}">
            <p class="mt-1 text-sm text-gray-500">{{ __('admin.seo.meta_keywords_help') }}</p>
        </div>
    </div>

    <!-- Tags -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">{{ __('admin.posts.tags') }}</label>
        @livewire('tag-selector', ['tags' => old('tags', $post->tags->pluck('name')->implode(', ') ?? '')])
    </div> 