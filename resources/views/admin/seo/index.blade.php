@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">SEO Settings</h1>
        <p class="text-gray-600 mt-1">Configure search engine optimization settings for your website</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.seo.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Settings --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- General SEO Settings --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-search text-purple-600 mr-2"></i>
                        General SEO Settings
                    </h2>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="seo_allow_indexing" 
                                   value="1"
                                   {{ $settings['seo_allow_indexing'] ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-purple-600 mr-2">
                            <span class="text-sm font-medium">Allow search engines to index this site</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-6 mt-1">Uncheck to block all search engines (robots.txt)</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Meta Title</label>
                        <input type="text" 
                               name="seo_default_meta_title" 
                               value="{{ $settings['seo_default_meta_title'] }}"
                               class="w-full rounded-md border-gray-300"
                               placeholder="Site Name - Tagline">
                        <p class="text-xs text-gray-500 mt-1">Used when page doesn't have a custom meta title (50-60 characters recommended)</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Meta Description</label>
                        <textarea name="seo_default_meta_description" 
                                  rows="3"
                                  class="w-full rounded-md border-gray-300"
                                  placeholder="A brief description of your website">{{ $settings['seo_default_meta_description'] }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Used when page doesn't have a custom meta description (150-160 characters recommended)</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Meta Keywords</label>
                        <input type="text" 
                               name="seo_default_meta_keywords" 
                               value="{{ $settings['seo_default_meta_keywords'] }}"
                               class="w-full rounded-md border-gray-300"
                               placeholder="keyword1, keyword2, keyword3">
                        <p class="text-xs text-gray-500 mt-1">Comma-separated keywords (less important for modern SEO)</p>
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-share-alt text-purple-600 mr-2"></i>
                        Social Media Settings
                    </h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Open Graph Image</label>
                        <input type="text" 
                               name="seo_og_default_image" 
                               value="{{ $settings['seo_og_default_image'] }}"
                               class="w-full rounded-md border-gray-300"
                               placeholder="/images/og-default.jpg">
                        <p class="text-xs text-gray-500 mt-1">Default image for social sharing (1200x630px recommended)</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Handle</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">@</span>
                            <input type="text" 
                                   name="seo_twitter_handle" 
                                   value="{{ $settings['seo_twitter_handle'] }}"
                                   class="w-full pl-8 rounded-md border-gray-300"
                                   placeholder="username">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Your Twitter username (without @)</p>
                    </div>
                </div>

                {{-- Analytics --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                        Analytics & Tracking
                    </h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                        <input type="text" 
                               name="seo_google_analytics_id" 
                               value="{{ $settings['seo_google_analytics_id'] }}"
                               class="w-full rounded-md border-gray-300"
                               placeholder="G-XXXXXXXXXX or UA-XXXXXXXXX-X">
                        <p class="text-xs text-gray-500 mt-1">Google Analytics Measurement ID</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Tag Manager ID</label>
                        <input type="text" 
                               name="seo_google_tag_manager_id" 
                               value="{{ $settings['seo_google_tag_manager_id'] }}"
                               class="w-full rounded-md border-gray-300"
                               placeholder="GTM-XXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">Google Tag Manager Container ID</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook Pixel ID</label>
                        <input type="text" 
                               name="seo_facebook_pixel_id" 
                               value="{{ $settings['seo_facebook_pixel_id'] }}"
                               class="w-full rounded-md border-gray-300"
                               placeholder="XXXXXXXXXXXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">Facebook Pixel ID for tracking</p>
                    </div>
                </div>
            </div>

            {{-- Sidebar Info & Actions --}}
            <div class="space-y-6">
                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-semibold mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('sitemap') }}" 
                           target="_blank"
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded transition">
                            <i class="fas fa-sitemap mr-2"></i>View Sitemap
                        </a>
                        
                        <a href="{{ route('robots') }}" 
                           target="_blank"
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded transition">
                            <i class="fas fa-robot mr-2"></i>View Robots.txt
                        </a>
                        
                        <form action="{{ route('admin.seo.regenerate-sitemap') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                                <i class="fas fa-sync-alt mr-2"></i>Regenerate Sitemap
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SEO Tips --}}
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-lg shadow-md p-6">
                    <h3 class="font-semibold mb-3 flex items-center text-purple-900">
                        <i class="fas fa-lightbulb mr-2"></i>
                        SEO Tips
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Keep meta titles under 60 characters</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Keep meta descriptions under 160 characters</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Use descriptive, keyword-rich URLs</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Add alt text to all images</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Submit sitemap to Google Search Console</span>
                        </li>
                    </ul>
                </div>

                {{-- Save Button --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-3 rounded font-semibold transition">
                        <i class="fas fa-save mr-2"></i>Save SEO Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
