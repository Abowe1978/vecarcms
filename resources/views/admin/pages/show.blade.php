@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">{{ $page->title }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-edit mr-2"></i>
                    {{ __('admin.pages.edit') }}
                </a>
                <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-list mr-2"></i>
                    {{ __('admin.pages.back_to_pages') }}
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 mt-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('admin.pages.page_details') }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('admin.pages.show_description') }}
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.title_label') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->title }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.slug') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->slug }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.status') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $page->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $page->is_published ? __('admin.pages.published') : __('admin.pages.draft') }}
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.template_label') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->template }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.parent_label') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->parent ? $page->parent->title : '-' }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.order_label') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->order }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.created_at') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.updated_at') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    @if($page->featured_image)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.featured_image') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="w-full max-w-md">
                                <img src="{{ $page->getImageUrl() }}" alt="{{ $page->title }}" class="rounded-lg shadow-md">
                            </div>
                        </dd>
                    </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.pages.content_label') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 prose max-w-none">
                            {!! $page->content !!}
                        </dd>
                    </div>
                </dl>
            </div>
            
            @if($page->meta_title || $page->meta_description || $page->meta_keywords)
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('admin.seo.title') }}
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    @if($page->meta_title)
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.seo.meta_title') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->meta_title }}
                        </dd>
                    </div>
                    @endif
                    
                    @if($page->meta_description)
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.seo.meta_description') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->meta_description }}
                        </dd>
                    </div>
                    @endif
                    
                    @if($page->meta_keywords)
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('admin.seo.meta_keywords') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $page->meta_keywords }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif
            
            @if($page->children->count() > 0)
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('admin.pages.child_pages') }}
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.pages.table.title') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.pages.table.slug') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.pages.table.status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.pages.table.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($page->children as $childPage)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $childPage->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $childPage->slug }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $childPage->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $childPage->is_published ? __('admin.pages.published') : __('admin.pages.draft') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.pages.show', $childPage) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('admin.pages.view') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
